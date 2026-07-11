<?php
declare(strict_types=1);
namespace App\ControlAcceso\Repository;

use PDO;
use Core\Database\Conexion;

class ControlAccesoRepository {
    private readonly PDO $conexion;

    public function __construct() {
        $this->conexion = Conexion::obtenerConexion('security');
    }

    public function obtener_roles(): array {
        $stmt = $this->conexion->query("SELECT id, nombre_rol, descripcion FROM roles ORDER BY id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtener_modulos(): array {
        $stmt = $this->conexion->query("SELECT id, nombre, descripcion FROM modulos ORDER BY id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtener_permisos(): array {
        $stmt = $this->conexion->query("SELECT id, nombre, descripcion FROM permisos ORDER BY id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtener_permisos_por_rol(int $idRol): array {
        $stmt = $this->conexion->prepare("SELECT id_modulo, id_permiso FROM rol_modulo_permiso WHERE id_rol = :id_rol");
        $stmt->bindValue(':id_rol', $idRol, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function tienePermiso(string $nombreRol, string $modulo, string $permiso): bool {
        $query = "SELECT 1
                  FROM rol_modulo_permiso rmp
                  JOIN roles r ON rmp.id_rol = r.id
                  JOIN modulos m ON rmp.id_modulo = m.id
                  JOIN permisos p ON rmp.id_permiso = p.id
                  WHERE r.nombre_rol = :rol AND m.nombre = :modulo AND p.nombre = :permiso
                  LIMIT 1";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':rol', $nombreRol, PDO::PARAM_STR);
        $stmt->bindValue(':modulo', $modulo, PDO::PARAM_STR);
        $stmt->bindValue(':permiso', $permiso, PDO::PARAM_STR);
        $stmt->execute();
        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function guardar_permisos(int $idRol, array $permisos): void {
        $this->conexion->beginTransaction();
        try {
            $deleteStmt = $this->conexion->prepare("DELETE FROM rol_modulo_permiso WHERE id_rol = :id_rol");
            $deleteStmt->bindValue(':id_rol', $idRol, PDO::PARAM_INT);
            $deleteStmt->execute();

            if (!empty($permisos)) {
                $insertStmt = $this->conexion->prepare(
                    "INSERT INTO rol_modulo_permiso (id_rol, id_modulo, id_permiso)
                     VALUES (:id_rol, :id_modulo, :id_permiso)"
                );
                foreach ($permisos as $perm) {
                    $insertStmt->bindValue(':id_rol', $idRol, PDO::PARAM_INT);
                    $insertStmt->bindValue(':id_modulo', (int) $perm['id_modulo'], PDO::PARAM_INT);
                    $insertStmt->bindValue(':id_permiso', (int) $perm['id_permiso'], PDO::PARAM_INT);
                    $insertStmt->execute();
                }
            }

            $this->conexion->commit();
        } catch (\Exception $e) {
            $this->conexion->rollBack();
            throw $e;
        }
    }
}
