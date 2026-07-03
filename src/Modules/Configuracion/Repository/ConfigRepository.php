<?php
declare(strict_types= 1);
namespace App\Configuracion\Repository;
use PDO;
use Core\Database\Conexion;

class ConfigRepository{
    private readonly PDO $conexion;
    public function __construct(){
        $this->conexion = Conexion::obtenerConexion('security');
    }

    // ==================== ROLES ====================
    public function obtenerRoles(): array {
        $stmt = $this->conexion->query("SELECT id, nombre_rol, descripcion FROM roles ORDER BY id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerRolPorId(int $id): ?array {
        $stmt = $this->conexion->prepare("SELECT id, nombre_rol, descripcion FROM roles WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function crearRol(string $nombre_rol, ?string $descripcion): bool {
        $stmt = $this->conexion->prepare("INSERT INTO roles (nombre_rol, descripcion) VALUES (:nombre_rol, :descripcion)");
        return $stmt->execute([':nombre_rol' => $nombre_rol, ':descripcion' => $descripcion]);
    }

    public function actualizarRol(int $id, string $nombre_rol, ?string $descripcion): bool {
        $stmt = $this->conexion->prepare("UPDATE roles SET nombre_rol = :nombre_rol, descripcion = :descripcion WHERE id = :id");
        return $stmt->execute([':id' => $id, ':nombre_rol' => $nombre_rol, ':descripcion' => $descripcion]);
    }

    public function eliminarRol(int $id): bool {
        $stmt = $this->conexion->prepare("DELETE FROM roles WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function rolExiste(string $nombre_rol, ?int $idExcluir = null): bool {
        $sql = "SELECT COUNT(*) FROM roles WHERE nombre_rol = :nombre_rol";
        $params = [':nombre_rol' => $nombre_rol];
        if ($idExcluir !== null) {
            $sql .= " AND id != :id_excluir";
            $params[':id_excluir'] = $idExcluir;
        }
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    public function rolTieneUsuarios(int $id): bool {
        $stmt = $this->conexion->prepare("SELECT COUNT(*) FROM usuarios WHERE rol_id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetchColumn() > 0;
    }

    // ==================== MODULOS ====================
    public function obtenerModulos(): array {
        $stmt = $this->conexion->query("SELECT id, nombre, descripcion FROM modulos ORDER BY id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerModuloPorId(int $id): ?array {
        $stmt = $this->conexion->prepare("SELECT id, nombre, descripcion FROM modulos WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function crearModulo(string $nombre, ?string $descripcion): bool {
        $stmt = $this->conexion->prepare("INSERT INTO modulos (nombre, descripcion) VALUES (:nombre, :descripcion)");
        return $stmt->execute([':nombre' => $nombre, ':descripcion' => $descripcion]);
    }

    public function actualizarModulo(int $id, string $nombre, ?string $descripcion): bool {
        $stmt = $this->conexion->prepare("UPDATE modulos SET nombre = :nombre, descripcion = :descripcion WHERE id = :id");
        return $stmt->execute([':id' => $id, ':nombre' => $nombre, ':descripcion' => $descripcion]);
    }

    public function moduloExiste(string $nombre, ?int $idExcluir = null): bool {
        $sql = "SELECT COUNT(*) FROM modulos WHERE nombre = :nombre";
        $params = [':nombre' => $nombre];
        if ($idExcluir !== null) {
            $sql .= " AND id != :id_excluir";
            $params[':id_excluir'] = $idExcluir;
        }
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    public function eliminarModulo(int $id): bool {
        $stmt = $this->conexion->prepare("DELETE FROM modulos WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // ==================== PERMISOS ====================
    public function obtenerPermisos(): array {
        $stmt = $this->conexion->query("SELECT id, nombre, descripcion FROM permisos ORDER BY id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPermisoPorId(int $id): ?array {
        $stmt = $this->conexion->prepare("SELECT id, nombre, descripcion FROM permisos WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function crearPermiso(string $nombre, ?string $descripcion): bool {
        $stmt = $this->conexion->prepare("INSERT INTO permisos (nombre, descripcion) VALUES (:nombre, :descripcion)");
        return $stmt->execute([':nombre' => $nombre, ':descripcion' => $descripcion]);
    }

    public function actualizarPermiso(int $id, string $nombre, ?string $descripcion): bool {
        $stmt = $this->conexion->prepare("UPDATE permisos SET nombre = :nombre, descripcion = :descripcion WHERE id = :id");
        return $stmt->execute([':id' => $id, ':nombre' => $nombre, ':descripcion' => $descripcion]);
    }

    public function permisoExiste(string $nombre, ?int $idExcluir = null): bool {
        $sql = "SELECT COUNT(*) FROM permisos WHERE nombre = :nombre";
        $params = [':nombre' => $nombre];
        if ($idExcluir !== null) {
            $sql .= " AND id != :id_excluir";
            $params[':id_excluir'] = $idExcluir;
        }
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    public function eliminarPermiso(int $id): bool {
        $stmt = $this->conexion->prepare("DELETE FROM permisos WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
