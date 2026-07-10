<?php
declare(strict_types=1);
namespace App\AsignacionDocente\Repository;

use PDO;
use Core\Database\Conexion;

class AsignacionDocenteRepository {
    private readonly PDO $conexion;

    public function __construct() {
        $this->conexion = Conexion::obtenerConexion('business');
    }

    public function consultar(): array {
        $query = "SELECT a.id, a.id_seccion, a.id_docente, a.id_unidad_curricular,
                         s.codigo_seccion, s.turno,
                         p.nombre AS periodo,
                         t.nombre AS trayecto,
                         uc.codigo AS uc_codigo, uc.nombre AS uc_nombre,
                         u.nombre AS docente_nombre, u.apellido AS docente_apellido,
                         u.tipo_cedula AS docente_tipo_cedula, u.cedula AS docente_cedula
                  FROM asignaciones_docentes a
                  JOIN secciones s ON a.id_seccion = s.id
                  JOIN periodos_academicos p ON s.id_periodo = p.id
                  JOIN trayectos t ON s.id_trayecto = t.id
                  JOIN unidades_curriculares uc ON a.id_unidad_curricular = uc.id
                  JOIN docentes d ON a.id_docente = d.id
                  JOIN cev_security.usuarios u ON d.id_usuario = u.id
                  ORDER BY p.nombre DESC, t.id, s.codigo_seccion, uc.codigo";
        $stmt = $this->conexion->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtener_por_seccion(int $idSeccion): array {
        $query = "SELECT a.id, a.id_seccion, a.id_docente, a.id_unidad_curricular
                  FROM asignaciones_docentes a
                  WHERE a.id_seccion = :id_seccion";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':id_seccion', $idSeccion, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function guardar_asignaciones(int $idSeccion, array $asignaciones): void {
        $this->conexion->beginTransaction();
        try {
            $deleteStmt = $this->conexion->prepare("DELETE FROM asignaciones_docentes WHERE id_seccion = :id_seccion");
            $deleteStmt->bindValue(':id_seccion', $idSeccion, PDO::PARAM_INT);
            $deleteStmt->execute();

            $insertStmt = $this->conexion->prepare(
                "INSERT INTO asignaciones_docentes (id_seccion, id_docente, id_unidad_curricular)
                 VALUES (:id_seccion, :id_docente, :id_unidad_curricular)"
            );

            foreach ($asignaciones as $asig) {
                if ($asig['id_docente'] === null) continue;

                $insertStmt->bindValue(':id_seccion', $idSeccion, PDO::PARAM_INT);
                $insertStmt->bindValue(':id_docente', $asig['id_docente'], PDO::PARAM_INT);
                $insertStmt->bindValue(':id_unidad_curricular', $asig['id_unidad_curricular'], PDO::PARAM_INT);
                $insertStmt->execute();
            }

            $this->conexion->commit();
        } catch (\Exception $e) {
            $this->conexion->rollBack();
            throw $e;
        }
    }

    public function eliminar(int $id): bool {
        $stmt = $this->conexion->prepare("DELETE FROM asignaciones_docentes WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function obtener_docentes_activos(): array {
        $query = "SELECT d.id, u.nombre, u.apellido, u.tipo_cedula, u.cedula
                  FROM docentes d
                  JOIN cev_security.usuarios u ON d.id_usuario = u.id
                  WHERE d.estado = 'Activo'
                  ORDER BY u.nombre, u.apellido";
        $stmt = $this->conexion->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtener_uc_por_trayecto(int $idTrayecto): array {
        $query = "SELECT DISTINCT uc.id, uc.codigo, uc.nombre, uc.unidades_credito
                  FROM unidades_curriculares uc
                  JOIN unidad_curricular_fases ucf ON uc.id = ucf.id_unidad_curricular
                  JOIN fases f ON ucf.id_fase = f.id
                  WHERE f.id_trayecto = :id_trayecto
                  ORDER BY uc.codigo";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':id_trayecto', $idTrayecto, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtener_periodos_activos(): array {
        $query = "SELECT id, nombre FROM periodos_academicos WHERE estado = 'Activo' ORDER BY id DESC";
        $stmt = $this->conexion->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtener_secciones_por_periodo(int $idPeriodo): array {
        $query = "SELECT s.id, s.codigo_seccion, s.turno, t.nombre AS trayecto
                  FROM secciones s
                  JOIN trayectos t ON s.id_trayecto = t.id
                  WHERE s.id_periodo = :id_periodo
                  ORDER BY t.id, s.codigo_seccion";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':id_periodo', $idPeriodo, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtener_seccion_por_id(int $idSeccion): ?array {
        $query = "SELECT s.id, s.id_periodo, s.id_trayecto, s.codigo_seccion, s.turno,
                         p.nombre AS periodo, t.nombre AS trayecto
                  FROM secciones s
                  JOIN periodos_academicos p ON s.id_periodo = p.id
                  JOIN trayectos t ON s.id_trayecto = t.id
                  WHERE s.id = :id_seccion";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':id_seccion', $idSeccion, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }
}
