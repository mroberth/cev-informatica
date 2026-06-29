<?php
declare(strict_types=1);

namespace App\Auth\Repository;

use PDO;
use Core\Database\Conexion;

class JwtBlacklistRepository{
    private readonly PDO $db;

    public function __construct(){
        $this->db = Conexion::obtenerConexion('security');
    }

    public function guardarEnListaNegra(string $firmaHash, string $expiracion): bool{
        $sql = "INSERT INTO jwt_blacklist (token_signature_hash, expiracion)
                VALUES (:token_signature_hash, :expiracion)
                ON DUPLICATE KEY UPDATE expiracion = :expiracion_dup";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':token_signature_hash' => $firmaHash,
            ':expiracion' => $expiracion,
            ':expiracion_dup' => $expiracion
        ]);
    }

    public function estaEnListaNegra(string $firmaHash): bool{
        $sql = "SELECT COUNT(*) FROM jwt_blacklist WHERE token_signature_hash = :token_signature_hash LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':token_signature_hash' => $firmaHash]);
        return (int) $stmt->fetchColumn() > 0;
    }
}
