<?php
declare(strict_types=1);

namespace App\Profesor\Repository;

use Core\Database\Conexion;
use PDO;

class ProfesorRepository
{
    private readonly PDO $conexion;

    public function __construct()
    {
        $this->conexion = Conexion::obtenerConexion('business');
    }

    public function obtenerMateriasAsignadas(int $idUsuario): array
    {
        $query = "SELECT ad.id AS asignacion_id, ad.id_unidad_curricular,
                         uc.codigo, uc.nombre, uc.unidades_credito,
                         t.nombre AS trayecto, s.codigo_seccion, s.turno,
                         per.nombre AS periodo,
                         (SELECT COUNT(*) FROM recursos_materia r WHERE r.id_asignacion_docente = ad.id) AS total_recursos
                  FROM docentes d
                  JOIN asignaciones_docentes ad ON d.id = ad.id_docente
                  JOIN unidades_curriculares uc ON ad.id_unidad_curricular = uc.id
                  JOIN secciones s ON ad.id_seccion = s.id
                  JOIN trayectos t ON s.id_trayecto = t.id
                  JOIN periodos_academicos per ON s.id_periodo = per.id
                  WHERE d.id_usuario = :idUsuario
                  ORDER BY per.nombre DESC, t.nombre, uc.nombre";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerResumenDocente(int $idUsuario): ?array
    {
        $query = "SELECT d.id AS docente_id, d.especialidad,
                         u.nombre, u.apellido, u.correo
                  FROM docentes d
                  JOIN cev_security.usuarios u ON d.id_usuario = u.id
                  WHERE d.id_usuario = :idUsuario
                  LIMIT 1";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }
}
