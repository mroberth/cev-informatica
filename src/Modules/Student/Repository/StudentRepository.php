<?php
declare(strict_types=1);

namespace App\Student\Repository;

use Core\Database\Conexion;
use PDO;

class StudentRepository
{
    private readonly PDO $conexion;

    public function __construct()
    {
        $this->conexion = Conexion::obtenerConexion('business');
    }

    public function obtenerMaterias(int $idUsuario): array
    {
        $query = "SELECT DISTINCT
                    uc.id,
                    uc.codigo,
                    uc.nombre,
                    uc.unidades_credito,
                    t.nombre AS trayecto,
                    ucf_agrupado.fase_nombre AS fase,
                    prof.nombre AS profesor_nombre,
                    prof.apellido AS profesor_apellido,
                    ad.id AS asignacion_id
                  FROM estudiantes e
                  JOIN inscripciones i ON e.id = i.id_estudiante AND i.estado = 'Cursando'
                  JOIN secciones s ON i.id_seccion = s.id
                  JOIN trayectos t ON s.id_trayecto = t.id
                  JOIN asignaciones_docentes ad ON ad.id_seccion = s.id
                  JOIN unidades_curriculares uc ON ad.id_unidad_curricular = uc.id
                  LEFT JOIN (
                      SELECT ucf.id_unidad_curricular, MIN(f.nombre) AS fase_nombre
                      FROM unidad_curricular_fases ucf
                      JOIN fases f ON ucf.id_fase = f.id
                      GROUP BY ucf.id_unidad_curricular
                  ) ucf_agrupado ON ucf_agrupado.id_unidad_curricular = uc.id
                  LEFT JOIN docentes d ON ad.id_docente = d.id
                  LEFT JOIN cev_security.usuarios prof ON d.id_usuario = prof.id
                  WHERE e.id_usuario = :idUsuario
                  ORDER BY uc.nombre";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function ($row) {
            $profesor = '';
            if (!empty($row['profesor_nombre']) || !empty($row['profesor_apellido'])) {
                $profesor = trim(($row['profesor_nombre'] ?? '') . ' ' . ($row['profesor_apellido'] ?? ''));
            }
            return [
                'id' => (int) $row['id'],
                'codigo' => $row['codigo'],
                'nombre' => $row['nombre'],
                'unidades_credito' => (int) $row['unidades_credito'],
                'trayecto' => $row['trayecto'],
                'fase' => $row['fase'],
                'profesor' => $profesor ?: 'Sin profesor asignado',
            ];
        }, $rows);
    }

    public function obtenerTrayectoActual(int $idUsuario): ?array
    {
        $query = "SELECT
                    t.nombre AS trayecto,
                    s.codigo_seccion,
                    s.turno
                  FROM estudiantes e
                  JOIN inscripciones i ON e.id = i.id_estudiante AND i.estado = 'Cursando'
                  JOIN secciones s ON i.id_seccion = s.id
                  JOIN trayectos t ON s.id_trayecto = t.id
                  WHERE e.id_usuario = :idUsuario
                  LIMIT 1";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }

    public function obtenerProximasEvaluaciones(int $idUsuario): array
    {
        $query = "SELECT e.id, e.titulo, e.tipo, e.porcentaje, e.fecha_estimada,
                         uc.nombre AS materia_nombre, uc.codigo AS materia_codigo,
                         uc.id AS materia_id,
                         DATEDIFF(e.fecha_estimada, NOW()) AS dias_restantes,
                         c.nota, c.id AS calificacion_id
                  FROM estudiantes est
                  JOIN inscripciones i ON est.id = i.id_estudiante AND i.estado = 'Cursando'
                  JOIN secciones s ON i.id_seccion = s.id
                  JOIN asignaciones_docentes ad ON ad.id_seccion = s.id
                  JOIN evaluaciones e ON e.id_asignacion = ad.id
                  JOIN unidades_curriculares uc ON ad.id_unidad_curricular = uc.id
                  LEFT JOIN calificaciones c ON c.id_evaluacion = e.id AND c.id_estudiante = est.id
                  WHERE est.id_usuario = :idUsuario
                    AND (e.fecha_estimada >= DATE_SUB(NOW(), INTERVAL 7 DAY) OR c.id IS NULL)
                  ORDER BY e.fecha_estimada ASC
                  LIMIT 10";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerResumenNotas(int $idUsuario): array
    {
        $query = "SELECT uc.id AS materia_id, uc.nombre AS materia_nombre, uc.codigo AS materia_codigo,
                         COUNT(DISTINCT e.id) AS total_evaluaciones,
                         COUNT(c.id) AS evaluaciones_calificadas,
                         ROUND(AVG(c.nota), 1) AS promedio
                  FROM estudiantes est
                  JOIN inscripciones i ON est.id = i.id_estudiante AND i.estado = 'Cursando'
                  JOIN secciones s ON i.id_seccion = s.id
                  JOIN asignaciones_docentes ad ON ad.id_seccion = s.id
                  JOIN evaluaciones e ON e.id_asignacion = ad.id
                  JOIN unidades_curriculares uc ON ad.id_unidad_curricular = uc.id
                  LEFT JOIN calificaciones c ON c.id_evaluacion = e.id AND c.id_estudiante = est.id
                  WHERE est.id_usuario = :idUsuario
                  GROUP BY uc.id, uc.nombre, uc.codigo
                  ORDER BY uc.nombre";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
