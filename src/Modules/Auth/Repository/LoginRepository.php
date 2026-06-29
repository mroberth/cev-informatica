<?php
declare(strict_types=1);

namespace App\Auth\Repository;

use PDO;
use Core\Database\Conexion;

class LoginRepository{
    private readonly PDO $db;

    public function __construct(){
        $this->db = Conexion::obtenerConexion('security');
    }

    public function buscarPorCorreo(string $correo): ?\UserDTO{
        $sql = "SELECT u.id, u.correo, u.nombre, u.apellido, u.password_hash, u.estado, r.nombre_rol
                FROM usuarios u
                JOIN roles r ON u.rol_id = r.id
                WHERE u.correo = :correo AND u.estado = 'activo'
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':correo' => $correo]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$user){
            return null;
        }

        return new \UserDTO(
            (int) $user['id'],
            $user['nombre'],
            $user['apellido'],
            $user['correo'],
            $user['password_hash'],
            $user['estado'],
            $user['nombre_rol']
        );
    }

    public function buscarPorId(int $id): ?\UserDTO{
        $sql = "SELECT u.id, u.correo, u.nombre, u.apellido, u.password_hash, u.estado, r.nombre_rol
                FROM usuarios u
                JOIN roles r ON u.rol_id = r.id
                WHERE u.id = :id AND u.estado = 'activo'
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$user){
            return null;
        }

        return new \UserDTO(
            (int) $user['id'],
            $user['nombre'],
            $user['apellido'],
            $user['correo'],
            $user['password_hash'],
            $user['estado'],
            $user['nombre_rol']
        );
    }
}
