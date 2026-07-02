<?php
namespace App\Bitacora\Repository;
use PDO;
use Core\Database\Conexion;

class BitacoraRepository{
    private readonly PDO $conexion;
    public function __construct(){
        $this->conexion = Conexion::obtenerConexion('security');
    }

    public function consultar_bitacora(): array {
        $query = "SELECT b.id, b.id_usuario, b.accion, b.descripcion, b.direccion_ip, b.user_agent AS navegador, b.creado_en AS fecha,
                    u.nombre, u.apellido, CONCAT(u.nombre, ' ', u.apellido) AS nombre_completo
                  FROM bitacora b
                  JOIN usuarios u ON b.id_usuario = u.id
                  ORDER BY b.creado_en DESC";
        $stmt = $this->conexion->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrar_bitacora($DTO): bool {
        $query = "INSERT INTO bitacora (id_usuario, accion, descripcion, direccion_ip, user_agent)
              VALUES (:id_usuario, :accion, :descripcion, :direccion_ip, :user_agent)";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':id_usuario', $DTO->getIdUsuario(), PDO::PARAM_INT);
        $stmt->bindValue(':accion', $DTO->getAccion(), PDO::PARAM_STR);
        $stmt->bindValue(':descripcion', $DTO->getDescripcion(), PDO::PARAM_STR);
        $stmt->bindValue(':direccion_ip', $DTO->getDireccionIp(), PDO::PARAM_STR);
        $stmt->bindValue(':user_agent', $DTO->getUserAgent(), PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}