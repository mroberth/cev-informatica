<?php
declare(strict_types=1);

namespace App\Auth\Repository;

use PDO;
use Core\Database\Conexion;

class RefreshTokenRepository{
    private readonly PDO $db;

    public function __construct(){
        $this->db = Conexion::obtenerConexion('security');
    }

    public function guardarToken(int $usuarioId, string $tokenHash, string $expiracion): bool{
        $sql = "INSERT INTO refresh_tokens (usuario_id, token_hash, expiracion, revocado)
                VALUES (:usuario_id, :token_hash, :expiracion, 0)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':usuario_id' => $usuarioId,
            ':token_hash' => $tokenHash,
            ':expiracion' => $expiracion
        ]);
    }

    public function buscarPorHashDeToken(string $tokenHash): ?\RefreshTokenDTO{
        $sql = "SELECT id, usuario_id, token_hash, expiracion, revocado, creado_en
                FROM refresh_tokens WHERE token_hash = :token_hash LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':token_hash' => $tokenHash]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$row){
            return null;
        }

        return new \RefreshTokenDTO(
            (int) $row['id'],
            (int) $row['usuario_id'],
            $row['token_hash'],
            $row['expiracion'],
            (bool) $row['revocado'],
            $row['creado_en']
        );
    }

    public function revocarPorHashDeToken(string $tokenHash): bool{
        $sql = "UPDATE refresh_tokens SET revocado = 1 WHERE token_hash = :token_hash";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':token_hash' => $tokenHash]);
    }
}
