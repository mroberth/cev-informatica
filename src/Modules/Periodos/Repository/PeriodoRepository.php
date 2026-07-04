<?php
declare(strict_types=1);
namespace App\Periodos\Repository;

use PDO;
use Core\Database\Conexion;
use App\Periodos\DTO\PeriodoDTO;

class PeriodoRepository {
    private readonly PDO $conexion;

    public function __construct() {
        $this->conexion = Conexion::obtenerConexion('business');
    }

    public function registrar(PeriodoDTO $periodo): int {
        $query = "INSERT INTO periodos_academicos (nombre, fecha_inicio, fecha_fin, estado)
                  VALUES (:nombre, :fecha_inicio, :fecha_fin, :estado)";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':nombre', $periodo->getNombre(), PDO::PARAM_STR);
        $stmt->bindValue(':fecha_inicio', $periodo->getFechaInicio(), PDO::PARAM_STR);
        $stmt->bindValue(':fecha_fin', $periodo->getFechaFin(), PDO::PARAM_STR);
        $stmt->bindValue(':estado', $periodo->getEstado(), PDO::PARAM_STR);
        $stmt->execute();
        return (int) $this->conexion->lastInsertId();
    }

    public function actualizar(PeriodoDTO $periodo): bool {
        $query = "UPDATE periodos_academicos
                  SET nombre = :nombre, fecha_inicio = :fecha_inicio,
                      fecha_fin = :fecha_fin, estado = :estado
                  WHERE id = :id";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':nombre', $periodo->getNombre(), PDO::PARAM_STR);
        $stmt->bindValue(':fecha_inicio', $periodo->getFechaInicio(), PDO::PARAM_STR);
        $stmt->bindValue(':fecha_fin', $periodo->getFechaFin(), PDO::PARAM_STR);
        $stmt->bindValue(':estado', $periodo->getEstado(), PDO::PARAM_STR);
        $stmt->bindValue(':id', $periodo->getId(), PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function obtener_por_id(int $id): ?array {
        $query = "SELECT id, nombre, fecha_inicio, fecha_fin, estado
                  FROM periodos_academicos WHERE id = :id";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }

    public function consultar(): array {
        $query = "SELECT id, nombre, fecha_inicio, fecha_fin, estado
                  FROM periodos_academicos ORDER BY id DESC";
        $stmt = $this->conexion->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function validar_nombre(string $nombre, ?int $idExcluir = null): bool {
        $sql = "SELECT id FROM periodos_academicos WHERE nombre = :nombre";
        $params = [':nombre' => $nombre];
        if ($idExcluir !== null) {
            $sql .= " AND id != :id_excluir";
            $params[':id_excluir'] = $idExcluir;
        }
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute($params);
        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
