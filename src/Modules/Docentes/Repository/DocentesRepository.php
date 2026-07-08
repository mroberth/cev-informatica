<?php
declare(strict_types=1);
namespace App\Docentes\Repository;

use App\Docentes\DTO\DocenteDTO;
use Core\Database\Conexion;
use PDO;

class DocentesRepository{
    private readonly PDO $conexion;
    public function __construct(){
        $this->conexion = Conexion::obtenerConexion('business');
    }

    public function consultar_docentes(): array {
        $query = "SELECT u.id, u.nombre, u.apellido, u.tipo_cedula, u.cedula
              FROM cev_security.usuarios u
              WHERE u.rol_id = 2
                AND u.id NOT IN (
                    SELECT d.id_usuario FROM docentes d WHERE d.id_usuario IS NOT NULL
                )
              ORDER BY u.nombre, u.apellido";
        $stmt = $this->conexion->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrar(DocenteDTO $docente): int {
        $query = "INSERT INTO docentes (id_usuario, especialidad, estado)
                  VALUES (:id_usuario, :especialidad, :estado)";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':id_usuario', $docente->getIdUsuario(), PDO::PARAM_INT);
        $stmt->bindValue(':especialidad', $docente->getEspecialidad(), PDO::PARAM_STR);
        $stmt->bindValue(':estado', $docente->getEstado(), PDO::PARAM_STR);
        $stmt->execute();
        return (int) $this->conexion->lastInsertId();
    }

    public function obtener_docentes_registrados(): array {
        $query = "SELECT d.id, u.nombre, u.apellido, u.tipo_cedula, u.cedula,
                         d.especialidad, d.estado
                  FROM docentes d
                  JOIN cev_security.usuarios u ON d.id_usuario = u.id
                  ORDER BY u.nombre, u.apellido";
        $stmt = $this->conexion->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtener_por_id(int $id): ?array {
        $query = "SELECT d.id, d.id_usuario, d.especialidad, d.estado,
                         u.nombre, u.apellido, u.tipo_cedula, u.cedula
                  FROM docentes d
                  JOIN cev_security.usuarios u ON d.id_usuario = u.id
                  WHERE d.id = :id";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }

    public function actualizar(DocenteDTO $docente): bool {
        $query = "UPDATE docentes
                  SET especialidad = :especialidad, estado = :estado
                  WHERE id = :id";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':especialidad', $docente->getEspecialidad(), PDO::PARAM_STR);
        $stmt->bindValue(':estado', $docente->getEstado(), PDO::PARAM_STR);
        $stmt->bindValue(':id', $docente->getId(), PDO::PARAM_INT);
        return $stmt->execute();
    }
}
