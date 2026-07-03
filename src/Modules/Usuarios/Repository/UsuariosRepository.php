<?php
declare(strict_types= 1);
namespace App\Usuarios\Repository;
use App\Usuarios\DTO\UsuarioDTO;
use PDO;
use Core\Database\Conexion;


class UsuariosRepository {
    private readonly PDO $conexion;
    public function __construct(){
        $this->conexion = Conexion::obtenerConexion('security');
    }

    public function obtener_roles(): array {
        $query = "SELECT id, nombre_rol FROM roles";
        $stmt = $this->conexion->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function validar_correo(string $correo, ?int $idExcluir = null): bool {
        $sql = "SELECT correo FROM usuarios WHERE correo = :correo";
        $params = [':correo' => $correo];
        if ($idExcluir !== null) {
            $sql .= " AND id != :id_excluir";
            $params[':id_excluir'] = $idExcluir;
        }
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute($params);
        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function validar_cedula(string $tipoCedula, string $cedula, ?int $idExcluir = null): bool {
        $sql = "SELECT id FROM usuarios WHERE tipo_cedula = :tipo_cedula AND cedula = :cedula";
        $params = [':tipo_cedula' => $tipoCedula, ':cedula' => $cedula];
        if ($idExcluir !== null) {
            $sql .= " AND id != :id_excluir";
            $params[':id_excluir'] = $idExcluir;
        }
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute($params);
        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function validar_telefono(string $telefono, ?int $idExcluir = null): bool {
        $sql = "SELECT id FROM usuarios WHERE telefono = :telefono";
        $params = [':telefono' => $telefono];
        if ($idExcluir !== null) {
            $sql .= " AND id != :id_excluir";
            $params[':id_excluir'] = $idExcluir;
        }
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute($params);
        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function registrar_usuario(UsuarioDTO $usuario): int {
        $query = "INSERT INTO usuarios (tipo_cedula, cedula, nombre, apellido, correo, telefono, password_hash, rol_id, estado)
                  VALUES (:tipo_cedula, :cedula, :nombre, :apellido, :correo, :telefono, :password_hash, :rol_id, :estado)";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':tipo_cedula', $usuario->getTipoCedula(), PDO::PARAM_STR);
        $stmt->bindValue(':cedula', $usuario->getCedula(), PDO::PARAM_STR);
        $stmt->bindValue(':nombre', $usuario->getNombre(), PDO::PARAM_STR);
        $stmt->bindValue(':apellido', $usuario->getApellido(), PDO::PARAM_STR);
        $stmt->bindValue(':correo', $usuario->getCorreo(), PDO::PARAM_STR);
        $stmt->bindValue(':telefono', $usuario->getTelefono(), PDO::PARAM_STR);
        $stmt->bindValue('password_hash', password_hash($usuario->getPassword(), PASSWORD_DEFAULT), PDO::PARAM_STR);
        $stmt->bindValue(':rol_id', $usuario->getUsuarioId(), PDO::PARAM_INT);
        $stmt->bindValue(':estado', $usuario->getEstado(), PDO::PARAM_STR);
        $stmt->execute();

        return (int) $this->conexion->lastInsertId();
    }

    public function consultar_usuarios(): array {
        $query = "SELECT u.id, u.tipo_cedula, u.cedula, u.nombre, u.apellido, u.correo, u.telefono, u.rol_id, r.nombre_rol AS rol, u.estado
                  FROM usuarios u
                  JOIN roles r ON u.rol_id = r.id";
        $stmt = $this->conexion->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizar_usuario(UsuarioDTO $usuario, ?string $passwordNueva = null): bool {
        $campos = "tipo_cedula = :tipo_cedula, cedula = :cedula, nombre = :nombre, apellido = :apellido, correo = :correo, telefono = :telefono, rol_id = :rol_id, estado = :estado";

        if ($passwordNueva !== null && $passwordNueva !== '') {
            $campos .= ", password_hash = :password_hash";
        }

        $query = "UPDATE usuarios SET {$campos} WHERE id = :id";
        $stmt = $this->conexion->prepare($query);

        $stmt->bindValue(':id', $usuario->getId(), PDO::PARAM_INT);
        $stmt->bindValue(':tipo_cedula', $usuario->getTipoCedula(), PDO::PARAM_STR);
        $stmt->bindValue(':cedula', $usuario->getCedula(), PDO::PARAM_STR);
        $stmt->bindValue(':nombre', $usuario->getNombre(), PDO::PARAM_STR);
        $stmt->bindValue(':apellido', $usuario->getApellido(), PDO::PARAM_STR);
        $stmt->bindValue(':correo', $usuario->getCorreo(), PDO::PARAM_STR);
        $stmt->bindValue(':telefono', $usuario->getTelefono(), PDO::PARAM_STR);
        $stmt->bindValue(':rol_id', $usuario->getUsuarioId(), PDO::PARAM_INT);
        $stmt->bindValue(':estado', $usuario->getEstado(), PDO::PARAM_STR);

        if ($passwordNueva !== null && $passwordNueva !== '') {
            $stmt->bindValue(':password_hash', password_hash($passwordNueva, PASSWORD_DEFAULT), PDO::PARAM_STR);
        }

        return $stmt->execute();
    }

    public function contar_administradores_activos(): int {
        $query = "SELECT COUNT(*) FROM usuarios u
                  JOIN roles r ON u.rol_id = r.id
                  WHERE r.nombre_rol = 'Admin' AND u.estado = 'activo'";
        $stmt = $this->conexion->prepare($query);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function cambiar_estado(int $id, string $estado): bool {
        $query = "UPDATE usuarios SET estado = :estado WHERE id = :id";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':estado', $estado, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function obtener_usuario_por_id(int $id): ?array {
        $query = "SELECT u.id, u.tipo_cedula, u.cedula, u.nombre, u.apellido, u.correo, u.telefono, u.rol_id, r.nombre_rol AS rol, u.estado
                  FROM usuarios u
                  JOIN roles r ON u.rol_id = r.id
                  WHERE u.id = :id";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }
}
