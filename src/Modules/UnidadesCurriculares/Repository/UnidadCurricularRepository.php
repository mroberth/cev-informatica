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
        $this->conexion->beginTransaction();
        try {
            $query = "INSERT INTO unidades_curriculares (codigo, nombre, unidades_credito)
                      VALUES (:codigo, :nombre, :unidades_credito)";
            $stmt = $this->conexion->prepare($query);
            $stmt->bindValue(':codigo', $uc->getCodigo(), PDO::PARAM_STR);
            $stmt->bindValue(':nombre', $uc->getNombre(), PDO::PARAM_STR);
            $stmt->bindValue(':unidades_credito', $uc->getUnidadesCredito(), PDO::PARAM_INT);
            $stmt->execute();
            $idInsertado = (int) $this->conexion->lastInsertId();

            $this->insertarFases($idInsertado, $uc->getFases());

            $this->conexion->commit();
            return $idInsertado;
        } catch (\Exception $e) {
            $this->conexion->rollBack();
            throw $e;
        }
    }

    public function consultar(): array {
        $query = "SELECT uc.id, uc.codigo, uc.nombre, uc.unidades_credito,
                         GROUP_CONCAT(DISTINCT f.nombre ORDER BY f.id SEPARATOR ', ') AS fases_nombres,
                         GROUP_CONCAT(DISTINCT f.id ORDER BY f.id SEPARATOR ',') AS fases_ids,
                         MAX(t.id) AS id_trayecto, MAX(t.nombre) AS trayecto
                  FROM unidades_curriculares uc
                  JOIN unidad_curricular_fases ucf ON uc.id = ucf.id_unidad_curricular
                  JOIN fases f ON ucf.id_fase = f.id
                  JOIN trayectos t ON f.id_trayecto = t.id
                  GROUP BY uc.id
                  ORDER BY id_trayecto, MIN(f.id), uc.codigo";
        $stmt = $this->conexion->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtener_por_id(int $id): ?array {
        $query = "SELECT uc.id, uc.codigo, uc.nombre, uc.unidades_credito,
                         t.id AS id_trayecto, t.nombre AS trayecto
                  FROM unidades_curriculares uc
                  JOIN unidad_curricular_fases ucf ON uc.id = ucf.id_unidad_curricular
                  JOIN fases f ON ucf.id_fase = f.id
                  JOIN trayectos t ON f.id_trayecto = t.id
                  WHERE uc.id = :id
                  LIMIT 1";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $uc = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$uc) return null;

        $fasesQuery = "SELECT ucf.id_fase AS id, f.nombre
                       FROM unidad_curricular_fases ucf
                       JOIN fases f ON ucf.id_fase = f.id
                       WHERE ucf.id_unidad_curricular = :id
                       ORDER BY f.id";
        $stmtFases = $this->conexion->prepare($fasesQuery);
        $stmtFases->bindValue(':id', $id, PDO::PARAM_INT);
        $stmtFases->execute();
        $uc['fases'] = $stmtFases->fetchAll(PDO::FETCH_ASSOC);

        return $uc;
    }

    public function actualizar(UnidadCurricularDTO $uc): bool {
        $this->conexion->beginTransaction();
        try {
            $query = "UPDATE unidades_curriculares
                      SET codigo = :codigo, nombre = :nombre, unidades_credito = :unidades_credito
                      WHERE id = :id";
            $stmt = $this->conexion->prepare($query);
            $stmt->bindValue(':codigo', $uc->getCodigo(), PDO::PARAM_STR);
            $stmt->bindValue(':nombre', $uc->getNombre(), PDO::PARAM_STR);
            $stmt->bindValue(':unidades_credito', $uc->getUnidadesCredito(), PDO::PARAM_INT);
            $stmt->bindValue(':id', $uc->getId(), PDO::PARAM_INT);
            $stmt->execute();

            $deleteStmt = $this->conexion->prepare("DELETE FROM unidad_curricular_fases WHERE id_unidad_curricular = :id");
            $deleteStmt->bindValue(':id', $uc->getId(), PDO::PARAM_INT);
            $deleteStmt->execute();

            $this->insertarFases($uc->getId(), $uc->getFases());

            $this->conexion->commit();
            return true;
        } catch (\Exception $e) {
            $this->conexion->rollBack();
            throw $e;
        }
    }

    private function insertarFases(int $idUnidadCurricular, array $fases): void {
        $insertStmt = $this->conexion->prepare(
            "INSERT INTO unidad_curricular_fases (id_unidad_curricular, id_fase) VALUES (:id_uc, :id_fase)"
        );
        foreach ($fases as $faseId) {
            $insertStmt->bindValue(':id_uc', $idUnidadCurricular, PDO::PARAM_INT);
            $insertStmt->bindValue(':id_fase', $faseId, PDO::PARAM_INT);
            $insertStmt->execute();
        }
    }
}
