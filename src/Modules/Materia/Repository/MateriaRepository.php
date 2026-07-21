<?php
declare(strict_types=1);

namespace App\Materia\Repository;

use App\Materia\DTO\CalificacionDTO;
use App\Materia\DTO\EntregaDTO;
use App\Materia\DTO\EvaluacionDTO;
use App\Materia\DTO\RecursoDTO;
use Core\Database\Conexion;
use PDO;

class MateriaRepository
{
    private readonly PDO $conexion;

    public function __construct()
    {
        $this->conexion = Conexion::obtenerConexion('business');
    }

    public function obtenerAsignacionEstudiante(int $idUsuario, int $idUc): ?array
    {
        $query = "SELECT ad.id, ad.id_seccion, ad.id_docente, ad.id_unidad_curricular,
                         uc.codigo, uc.nombre AS uc_nombre, uc.unidades_credito,
                         t.nombre AS trayecto, s.codigo_seccion
                  FROM estudiantes e
                  JOIN inscripciones i ON e.id = i.id_estudiante AND i.estado = 'Cursando'
                  JOIN secciones s ON i.id_seccion = s.id
                  JOIN trayectos t ON s.id_trayecto = t.id
                  JOIN asignaciones_docentes ad ON ad.id_seccion = s.id
                  JOIN unidades_curriculares uc ON ad.id_unidad_curricular = uc.id
                  WHERE e.id_usuario = :idUsuario AND uc.id = :idUc
                  LIMIT 1";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmt->bindValue(':idUc', $idUc, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }

    public function obtenerRecursos(int $idAsignacion): array
    {
        $query = "SELECT r.*, u.nombre AS creador_nombre, u.apellido AS creador_apellido
                  FROM recursos_materia r
                  LEFT JOIN cev_security.usuarios u ON r.creado_por = u.id
                  WHERE r.id_asignacion_docente = :idAsignacion
                  ORDER BY r.creado_en DESC";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':idAsignacion', $idAsignacion, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerAsignacionPorId(int $idAsignacion): ?array
    {
        $query = "SELECT ad.*, uc.nombre AS uc_nombre, uc.codigo,
                         t.nombre AS trayecto, s.codigo_seccion
                  FROM asignaciones_docentes ad
                  JOIN unidades_curriculares uc ON ad.id_unidad_curricular = uc.id
                  JOIN secciones s ON ad.id_seccion = s.id
                  JOIN trayectos t ON s.id_trayecto = t.id
                  WHERE ad.id = :id";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':id', $idAsignacion, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }

    public function esDocenteAsignado(int $idAsignacion, int $idUsuario): bool
    {
        $query = "SELECT 1 FROM asignaciones_docentes ad
                  JOIN docentes d ON ad.id_docente = d.id
                  WHERE ad.id = :idAsignacion AND d.id_usuario = :idUsuario";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':idAsignacion', $idAsignacion, PDO::PARAM_INT);
        $stmt->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
        return (bool) $stmt->fetchColumn();
    }

    public function insertarRecurso(RecursoDTO $recurso): int
    {
        $query = "INSERT INTO recursos_materia
                    (id_asignacion_docente, titulo, descripcion, tipo, archivo_ruta, enlace_url, creado_por)
                  VALUES
                    (:id_asignacion_docente, :titulo, :descripcion, :tipo, :archivo_ruta, :enlace_url, :creado_por)";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':id_asignacion_docente', $recurso->getIdAsignacionDocente(), PDO::PARAM_INT);
        $stmt->bindValue(':titulo', $recurso->getTitulo(), PDO::PARAM_STR);
        $stmt->bindValue(':descripcion', $recurso->getDescripcion(), PDO::PARAM_STR);
        $stmt->bindValue(':tipo', $recurso->getTipo(), PDO::PARAM_STR);
        $stmt->bindValue(':archivo_ruta', $recurso->getArchivoRuta(), PDO::PARAM_STR);
        $stmt->bindValue(':enlace_url', $recurso->getEnlaceUrl(), PDO::PARAM_STR);
        $stmt->bindValue(':creado_por', $recurso->getCreadoPor(), PDO::PARAM_INT);
        $stmt->execute();
        return (int) $this->conexion->lastInsertId();
    }

    public function eliminarRecurso(int $idRecurso, int $creadoPor): bool
    {
        $query = "DELETE FROM recursos_materia WHERE id = :id AND creado_por = :creadoPor";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':id', $idRecurso, PDO::PARAM_INT);
        $stmt->bindValue(':creadoPor', $creadoPor, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function obtenerRecursoPorId(int $idRecurso): ?array
    {
        $query = "SELECT * FROM recursos_materia WHERE id = :id";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':id', $idRecurso, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }

    public function obtenerEvaluaciones(int $idAsignacion): array
    {
        $query = "SELECT e.*, u.nombre AS creador_nombre, u.apellido AS creador_apellido
                  FROM evaluaciones e
                  LEFT JOIN cev_security.usuarios u ON e.creado_por = u.id
                  WHERE e.id_asignacion = :idAsignacion
                  ORDER BY e.fecha_estimada ASC, e.creado_en DESC";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':idAsignacion', $idAsignacion, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertarEvaluacion(EvaluacionDTO $evaluacion): int
    {
        $query = "INSERT INTO evaluaciones
                    (id_asignacion, titulo, descripcion, tipo, porcentaje, fecha_estimada, creado_por)
                  VALUES
                    (:id_asignacion, :titulo, :descripcion, :tipo, :porcentaje, :fecha_estimada, :creado_por)";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':id_asignacion', $evaluacion->getIdAsignacionDocente(), PDO::PARAM_INT);
        $stmt->bindValue(':titulo', $evaluacion->getTitulo(), PDO::PARAM_STR);
        $stmt->bindValue(':descripcion', $evaluacion->getDescripcion(), PDO::PARAM_STR);
        $stmt->bindValue(':tipo', $evaluacion->getTipo(), PDO::PARAM_STR);
        $stmt->bindValue(':porcentaje', $evaluacion->getPorcentaje(), PDO::PARAM_STR);
        $stmt->bindValue(':fecha_estimada', $evaluacion->getFechaEntrega(), PDO::PARAM_STR);
        $stmt->bindValue(':creado_por', $evaluacion->getCreadoPor(), PDO::PARAM_INT);
        $stmt->execute();
        return (int) $this->conexion->lastInsertId();
    }

    public function eliminarEvaluacion(int $idEvaluacion, int $creadoPor): bool
    {
        $query = "DELETE FROM evaluaciones WHERE id = :id AND creado_por = :creadoPor";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':id', $idEvaluacion, PDO::PARAM_INT);
        $stmt->bindValue(':creadoPor', $creadoPor, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function obtenerEvaluacionPorId(int $idEvaluacion): ?array
    {
        $query = "SELECT * FROM evaluaciones WHERE id = :id";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':id', $idEvaluacion, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }

    public function obtenerEvaluacionesEstudiante(int $idUsuario): array
    {
        $query = "SELECT e.*, uc.id AS materia_id, uc.nombre AS materia_nombre, uc.codigo AS materia_codigo,
                         t.nombre AS trayecto, s.codigo_seccion
                  FROM estudiantes est
                  JOIN inscripciones i ON est.id = i.id_estudiante AND i.estado = 'Cursando'
                  JOIN secciones s ON i.id_seccion = s.id
                  JOIN asignaciones_docentes ad ON ad.id_seccion = s.id
                  JOIN evaluaciones e ON e.id_asignacion = ad.id
                  JOIN unidades_curriculares uc ON ad.id_unidad_curricular = uc.id
                  JOIN trayectos t ON s.id_trayecto = t.id
                  WHERE est.id_usuario = :idUsuario
                  ORDER BY e.fecha_estimada ASC";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerEstudiantesConCalificaciones(int $idEvaluacion, int $idAsignacion): array
    {
        $query = "SELECT est.id AS id_estudiante, u.nombre, u.apellido,
                         c.id AS calificacion_id, c.nota, c.observaciones
                  FROM estudiantes est
                  JOIN cev_security.usuarios u ON est.id_usuario = u.id
                  JOIN inscripciones i ON est.id = i.id_estudiante AND i.estado = 'Cursando'
                  JOIN asignaciones_docentes ad ON i.id_seccion = ad.id_seccion
                  LEFT JOIN calificaciones c ON c.id_evaluacion = :idEvaluacion AND c.id_estudiante = est.id
                  WHERE ad.id = :idAsignacion
                  ORDER BY u.apellido, u.nombre";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':idEvaluacion', $idEvaluacion, PDO::PARAM_INT);
        $stmt->bindValue(':idAsignacion', $idAsignacion, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function guardarCalificaciones(array $calificaciones): void
    {
        $query = "INSERT INTO calificaciones (id_evaluacion, id_estudiante, nota, observaciones)
                  VALUES (:id_evaluacion, :id_estudiante, :nota, :observaciones)
                  ON DUPLICATE KEY UPDATE nota = VALUES(nota), observaciones = VALUES(observaciones), actualizado_en = NOW()";
        $stmt = $this->conexion->prepare($query);

        foreach ($calificaciones as $c) {
            $stmt->bindValue(':id_evaluacion', $c->getIdEvaluacion(), PDO::PARAM_INT);
            $stmt->bindValue(':id_estudiante', $c->getIdEstudiante(), PDO::PARAM_INT);
            $stmt->bindValue(':nota', $c->getNota(), PDO::PARAM_STR);
            $stmt->bindValue(':observaciones', $c->getObservaciones(), PDO::PARAM_STR);
            $stmt->execute();
        }
    }

    public function obtenerNotasEstudiante(int $idUsuario, int $idAsignacion): array
    {
        $query = "SELECT e.id AS evaluacion_id, e.titulo, e.tipo, e.porcentaje, e.fecha_estimada,
                         c.nota, c.observaciones
                  FROM evaluaciones e
                  LEFT JOIN calificaciones c ON c.id_evaluacion = e.id
                      AND c.id_estudiante = (SELECT id FROM estudiantes WHERE id_usuario = :idUsuario)
                  WHERE e.id_asignacion = :idAsignacion
                  ORDER BY e.fecha_estimada ASC";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmt->bindValue(':idAsignacion', $idAsignacion, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function verificarEstudiantePuedeEntregar(int $idEvaluacion, int $idUsuario): bool
    {
        $query = "SELECT 1 FROM estudiantes est
                  JOIN inscripciones i ON est.id = i.id_estudiante AND i.estado = 'Cursando'
                  JOIN asignaciones_docentes ad ON i.id_seccion = ad.id_seccion
                  JOIN evaluaciones e ON e.id_asignacion = ad.id
                  WHERE e.id = :idEvaluacion AND est.id_usuario = :idUsuario";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':idEvaluacion', $idEvaluacion, PDO::PARAM_INT);
        $stmt->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
        return (bool) $stmt->fetchColumn();
    }

    public function insertarEntrega(EntregaDTO $entrega): int
    {
        $query = "INSERT INTO entregas (id_evaluacion, id_estudiante, archivo_ruta, archivo_nombre_original, comentario_alumno)
                  VALUES (:id_evaluacion, :id_estudiante, :archivo_ruta, :archivo_nombre_original, :comentario_alumno)
                  ON DUPLICATE KEY UPDATE archivo_ruta = VALUES(archivo_ruta), archivo_nombre_original = VALUES(archivo_nombre_original),
                                          comentario_alumno = VALUES(comentario_alumno), actualizado_en = NOW()";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':id_evaluacion', $entrega->getIdEvaluacion(), PDO::PARAM_INT);
        $stmt->bindValue(':id_estudiante', $entrega->getIdEstudiante(), PDO::PARAM_INT);
        $stmt->bindValue(':archivo_ruta', $entrega->getArchivoRuta(), PDO::PARAM_STR);
        $stmt->bindValue(':archivo_nombre_original', $entrega->getArchivoNombreOriginal(), PDO::PARAM_STR);
        $stmt->bindValue(':comentario_alumno', $entrega->getComentarioAlumno(), PDO::PARAM_STR);
        $stmt->execute();
        return (int) $this->conexion->lastInsertId();
    }

    public function obtenerEntregasEvaluacion(int $idEvaluacion): array
    {
        $query = "SELECT ent.*, u.nombre, u.apellido
                  FROM entregas ent
                  JOIN estudiantes est ON ent.id_estudiante = est.id
                  JOIN cev_security.usuarios u ON est.id_usuario = u.id
                  WHERE ent.id_evaluacion = :idEvaluacion
                  ORDER BY u.apellido, u.nombre";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':idEvaluacion', $idEvaluacion, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerEntregaEstudiante(int $idEvaluacion, int $idUsuario): ?array
    {
        $query = "SELECT ent.* FROM entregas ent
                  JOIN estudiantes est ON ent.id_estudiante = est.id
                  WHERE ent.id_evaluacion = :idEvaluacion AND est.id_usuario = :idUsuario";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':idEvaluacion', $idEvaluacion, PDO::PARAM_INT);
        $stmt->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function obtenerIdEstudiantePorUsuario(int $idUsuario): ?int
    {
        $stmt = $this->conexion->prepare("SELECT id FROM estudiantes WHERE id_usuario = :idUsuario");
        $stmt->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
        $id = $stmt->fetchColumn();
        return $id ? (int) $id : null;
    }

    public function verificarProfesorTieneAsignacion(int $idUsuario, int $idAsignacion): bool
    {
        $stmt = $this->conexion->prepare(
            "SELECT 1 FROM asignaciones_docentes ad
             JOIN docentes d ON ad.id_docente = d.id
             WHERE ad.id = :idAsignacion AND d.id_usuario = :idUsuario"
        );
        $stmt->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmt->bindValue(':idAsignacion', $idAsignacion, PDO::PARAM_INT);
        $stmt->execute();
        return (bool) $stmt->fetchColumn();
    }

    public function obtenerEstudiantesEnAsignacion(int $idAsignacion): array
    {
        $query = "SELECT est.id_usuario, u.nombre, u.apellido
                  FROM estudiantes est
                  JOIN inscripciones i ON est.id = i.id_estudiante AND i.estado = 'Cursando'
                  JOIN asignaciones_docentes ad ON i.id_seccion = ad.id_seccion
                  JOIN cev_security.usuarios u ON est.id_usuario = u.id
                  WHERE ad.id = :idAsignacion";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':idAsignacion', $idAsignacion, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerIdUsuarioPorEstudiante(int $idEstudiante): ?int
    {
        $stmt = $this->conexion->prepare("SELECT id_usuario FROM estudiantes WHERE id = :id");
        $stmt->bindValue(':id', $idEstudiante, PDO::PARAM_INT);
        $stmt->execute();
        $id = $stmt->fetchColumn();
        return $id ? (int) $id : null;
    }
}
