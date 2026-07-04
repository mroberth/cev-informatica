<?php
declare(strict_types=1);
namespace App\Trayectos\Repository;

use PDO;
use Core\Database\Conexion;

class TrayectoRepository {
    private readonly PDO $conexion;

    public function __construct() {
        $this->conexion = Conexion::obtenerConexion('business');
    }

    public function consultar_con_fases(): array {
        $query = "SELECT t.id AS trayecto_id, t.nombre AS trayecto, t.descripcion AS descripcion_trayecto,
                         f.id AS fase_id, f.nombre AS fase, f.descripcion AS descripcion_fase
                  FROM trayectos t
                  LEFT JOIN fases f ON t.id = f.id_trayecto
                  ORDER BY t.id, f.id";
        $stmt = $this->conexion->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
