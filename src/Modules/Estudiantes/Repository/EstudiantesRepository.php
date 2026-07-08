<?php
declare(strict_types=1);
namespace App\Estudiantes\Repository;

use App\Estudiantes\DTO\EstudianteDTO;
use Core\Database\Conexion;
use PDO;

class EstudiantesRepository{
    private readonly PDO $conexion;
    public function __construct(){
        $this->conexion = Conexion::obtenerConexion('business');
    }

    public function consultar_estudiantes(): array {
        $query = "SELECT u.id, u.nombre, u.apellido, u.tipo_cedula, u.cedula
              FROM cev_security.usuarios u
              WHERE u.rol_id = 3
                AND u.id NOT IN (
                    SELECT e.id_usuario FROM estudiantes e WHERE e.id_usuario IS NOT NULL
                )
              ORDER BY u.nombre, u.apellido";
        $stmt = $this->conexion->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrar(EstudianteDTO $estudiante): int {
        $query = "INSERT INTO estudiantes (id_usuario, estado_academico)
                  VALUES (:id_usuario, :estado_academico)";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':id_usuario', $estudiante->getIdUsuario(), PDO::PARAM_INT);
        $stmt->bindValue(':estado_academico', $estudiante->getEstadoAcademico(), PDO::PARAM_STR);
        $stmt->execute();
        return (int) $this->conexion->lastInsertId();
    }

    public function obtener_estudiantes_registrados(): array {
        $query = "SELECT e.id, u.nombre, u.apellido, u.tipo_cedula, u.cedula,
                         e.estado_academico
                  FROM estudiantes e
                  JOIN cev_security.usuarios u ON e.id_usuario = u.id
                  ORDER BY u.nombre, u.apellido";
        $stmt = $this->conexion->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtener_por_id(int $id): ?array {
        $query = "SELECT e.id, e.id_usuario, e.estado_academico,
                         u.nombre, u.apellido, u.tipo_cedula, u.cedula
                  FROM estudiantes e
                  JOIN cev_security.usuarios u ON e.id_usuario = u.id
                  WHERE e.id = :id";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }

    public function actualizar(EstudianteDTO $estudiante): bool {
        $query = "UPDATE estudiantes
                  SET estado_academico = :estado_academico
                  WHERE id = :id";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':estado_academico', $estudiante->getEstadoAcademico(), PDO::PARAM_STR);
        $stmt->bindValue(':id', $estudiante->getId(), PDO::PARAM_INT);
        return $stmt->execute();
    }
}
