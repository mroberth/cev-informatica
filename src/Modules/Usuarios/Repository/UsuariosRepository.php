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

    public function validar_correo(string $correo): bool {
        $query = "SELECT correo FROM usuarios WHERE correo = :correo";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':correo', $correo, PDO::PARAM_STR);
        $stmt->execute();
        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function registrar_usuario(UsuarioDTO $usuario): int {
        $query = "INSERT INTO usuarios (nombre, apellido, correo, password_hash, rol_id, estado)
                  VALUES (:nombre, :apellido, :correo, :password_hash, :rol_id, :estado)";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':nombre', $usuario->getNombre(), PDO::PARAM_STR);
        $stmt->bindValue(':apellido', $usuario->getApellido(), PDO::PARAM_STR);
        $stmt->bindValue(':correo', $usuario->getCorreo(), PDO::PARAM_STR);
        $stmt->bindValue('password_hash', password_hash($usuario->getPassword(), PASSWORD_DEFAULT), PDO::PARAM_STR);
        $stmt->bindValue(':rol_id', $usuario->getUsuarioId(), PDO::PARAM_INT);
        $stmt->bindValue(':estado', $usuario->getEstado(), PDO::PARAM_STR);
        $stmt->execute();

        return (int) $this->conexion->lastInsertId();
    }

}
