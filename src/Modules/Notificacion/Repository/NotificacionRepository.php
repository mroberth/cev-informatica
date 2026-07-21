<?php
declare(strict_types=1);

namespace App\Notificacion\Repository;

use Core\Database\Conexion;
use PDO;

class NotificacionRepository
{
    private readonly PDO $conexion;

    public function __construct()
    {
        $this->conexion = Conexion::obtenerConexion('business');
    }

    public function insertar(int $idUsuarioDestino, string $tipo, string $titulo, ?string $mensaje = null, ?int $idReferencia = null, ?string $tipoReferencia = null): int
    {
        $stmt = $this->conexion->prepare(
            "INSERT INTO notificaciones (id_usuario_destino, tipo, titulo, mensaje, id_referencia, tipo_referencia)
             VALUES (:id_usuario_destino, :tipo, :titulo, :mensaje, :id_referencia, :tipo_referencia)"
        );
        $stmt->bindValue(':id_usuario_destino', $idUsuarioDestino, PDO::PARAM_INT);
        $stmt->bindValue(':tipo', $tipo, PDO::PARAM_STR);
        $stmt->bindValue(':titulo', $titulo, PDO::PARAM_STR);
        $stmt->bindValue(':mensaje', $mensaje, PDO::PARAM_STR);
        $stmt->bindValue(':id_referencia', $idReferencia, $idReferencia === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
        $stmt->bindValue(':tipo_referencia', $tipoReferencia, $tipoReferencia === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->execute();
        return (int) $this->conexion->lastInsertId();
    }

    public function obtenerNoLeidas(int $idUsuario): array
    {
        $stmt = $this->conexion->prepare(
            "SELECT * FROM notificaciones WHERE id_usuario_destino = :idUsuario AND leido = 0 ORDER BY creado_en DESC"
        );
        $stmt->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerUltimas(int $idUsuario, int $limite = 20): array
    {
        $stmt = $this->conexion->prepare(
            "SELECT * FROM notificaciones WHERE id_usuario_destino = :idUsuario ORDER BY creado_en DESC LIMIT :limite"
        );
        $stmt->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarNoLeidas(int $idUsuario): int
    {
        $stmt = $this->conexion->prepare(
            "SELECT COUNT(*) FROM notificaciones WHERE id_usuario_destino = :idUsuario AND leido = 0"
        );
        $stmt->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function marcarLeida(int $id, int $idUsuario): void
    {
        $stmt = $this->conexion->prepare(
            "UPDATE notificaciones SET leido = 1 WHERE id = :id AND id_usuario_destino = :idUsuario"
        );
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function marcarTodasLeidas(int $idUsuario): void
    {
        $stmt = $this->conexion->prepare(
            "UPDATE notificaciones SET leido = 1 WHERE id_usuario_destino = :idUsuario AND leido = 0"
        );
        $stmt->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function notificacionesDesde(int $idUsuario, int $ultimoId): array
    {
        $stmt = $this->conexion->prepare(
            "SELECT * FROM notificaciones WHERE id_usuario_destino = :idUsuario AND id > :ultimoId ORDER BY id ASC"
        );
        $stmt->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmt->bindValue(':ultimoId', $ultimoId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
