<?php
declare(strict_types=1);
namespace App\UnidadesCurriculares\Repository;

use App\UnidadesCurriculares\DTO\UnidadCurricularDTO;
use PDO;
use Core\Database\Conexion;

class UnidadCurricularRepository {
    private readonly PDO $conexion;

    public function __construct(){
        $this->conexion = Conexion::obtenerConexion('business');
    }

    public function obtener_trayectos(): array {
        $stmt = $this->conexion->query("SELECT id, nombre FROM trayectos ORDER BY id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtener_fases_por_trayecto(int $idTrayecto): array {
        $stmt = $this->conexion->prepare(
            "SELECT f.id, f.nombre FROM fases f WHERE f.id_trayecto = :id_trayecto ORDER BY f.id"
        );
        $stmt->bindValue(':id_trayecto', $idTrayecto, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function validar_codigo(string $codigo, ?int $idExcluir = null): bool {
        $sql = "SELECT id FROM unidades_curriculares WHERE codigo = :codigo";
        $params = [':codigo' => $codigo];
        if ($idExcluir !== null) {
            $sql .= " AND id != :id_excluir";
            $params[':id_excluir'] = $idExcluir;
        }
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute($params);
        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function registrar(UnidadCurricularDTO $uc): int {
        $query = "INSERT INTO unidades_curriculares (id_fase, codigo, nombre, unidades_credito)
                  VALUES (:id_fase, :codigo, :nombre, :unidades_credito)";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':id_fase', $uc->getIdFase(), PDO::PARAM_INT);
        $stmt->bindValue(':codigo', $uc->getCodigo(), PDO::PARAM_STR);
        $stmt->bindValue(':nombre', $uc->getNombre(), PDO::PARAM_STR);
        $stmt->bindValue(':unidades_credito', $uc->getUnidadesCredito(), PDO::PARAM_INT);
        $stmt->execute();
        return (int) $this->conexion->lastInsertId();
    }

    public function consultar(): array {
        $query = "SELECT u.id, u.codigo, u.nombre, u.unidades_credito,
                         u.id_fase, f.nombre AS fase, t.id AS id_trayecto, t.nombre AS trayecto
                  FROM unidades_curriculares u
                  JOIN fases f ON u.id_fase = f.id
                  JOIN trayectos t ON f.id_trayecto = t.id
                  ORDER BY t.id, f.id, u.codigo";
        $stmt = $this->conexion->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtener_por_id(int $id): ?array {
        $query = "SELECT u.id, u.codigo, u.nombre, u.unidades_credito,
                         u.id_fase, f.nombre AS fase, t.id AS id_trayecto, t.nombre AS trayecto
                  FROM unidades_curriculares u
                  JOIN fases f ON u.id_fase = f.id
                  JOIN trayectos t ON f.id_trayecto = t.id
                  WHERE u.id = :id";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }

    public function actualizar(UnidadCurricularDTO $uc): bool {
        $query = "UPDATE unidades_curriculares
                  SET id_fase = :id_fase, codigo = :codigo, nombre = :nombre, unidades_credito = :unidades_credito
                  WHERE id = :id";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':id_fase', $uc->getIdFase(), PDO::PARAM_INT);
        $stmt->bindValue(':codigo', $uc->getCodigo(), PDO::PARAM_STR);
        $stmt->bindValue(':nombre', $uc->getNombre(), PDO::PARAM_STR);
        $stmt->bindValue(':unidades_credito', $uc->getUnidadesCredito(), PDO::PARAM_INT);
        $stmt->bindValue(':id', $uc->getId(), PDO::PARAM_INT);
        return $stmt->execute();
    }
}
