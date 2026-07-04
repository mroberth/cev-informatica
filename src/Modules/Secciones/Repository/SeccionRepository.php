<?php
declare(strict_types=1);
namespace App\Secciones\Repository;

use PDO;
use Core\Database\Conexion;
use App\Secciones\DTO\SeccionDTO;

class SeccionRepository {
    private readonly PDO $conexion;

    public function __construct() {
        $this->conexion = Conexion::obtenerConexion('business');
    }

    /**
     * Obtiene todos los periodos académicos activos para llenar el selector
     */
    public function obtener_periodos(): array {
        $query = "SELECT id, nombre, estado FROM periodos_academicos WHERE estado = 'Activo' ORDER BY id DESC";
        $stmt = $this->conexion->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene todos los trayectos académicos para llenar el selector
     */
    public function obtener_trayectos(): array {
        $query = "SELECT id, nombre, descripcion FROM trayectos ORDER BY id ASC";
        $stmt = $this->conexion->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrar(SeccionDTO $seccion): int {
        $query = "INSERT INTO secciones (id_periodo, id_trayecto, codigo_seccion, turno)
                  VALUES (:id_periodo, :id_trayecto, :codigo_seccion, :turno)";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':id_periodo', $seccion->getIdPeriodo(), PDO::PARAM_INT);
        $stmt->bindValue(':id_trayecto', $seccion->getIdTrayecto(), PDO::PARAM_INT);
        $stmt->bindValue(':codigo_seccion', $seccion->getCodigoSeccion(), PDO::PARAM_STR);
        $stmt->bindValue(':turno', $seccion->getTurno(), PDO::PARAM_STR);
        $stmt->execute();
        return (int) $this->conexion->lastInsertId();
    }

    public function obtener_por_id(int $id): ?array {
        $query = "SELECT s.id, s.codigo_seccion, s.turno,
                         s.id_periodo, p.nombre AS periodo,
                         s.id_trayecto, t.nombre AS trayecto
                  FROM secciones s
                  JOIN periodos_academicos p ON s.id_periodo = p.id
                  JOIN trayectos t ON s.id_trayecto = t.id
                  WHERE s.id = :id";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }

    public function consultar(): array {
        $query = "SELECT s.id, s.codigo_seccion, s.turno,
                         s.id_periodo, s.id_trayecto,
                         p.nombre AS periodo,
                         t.nombre AS trayecto
                  FROM secciones s
                  JOIN periodos_academicos p ON s.id_periodo = p.id
                  JOIN trayectos t ON s.id_trayecto = t.id
                  ORDER BY p.nombre DESC, t.id, s.codigo_seccion";
        $stmt = $this->conexion->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtener_periodos_todos(): array {
        $query = "SELECT id, nombre, estado FROM periodos_academicos ORDER BY id DESC";
        $stmt = $this->conexion->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizar(SeccionDTO $seccion): bool {
        $query = "UPDATE secciones
                  SET id_periodo = :id_periodo, id_trayecto = :id_trayecto,
                      codigo_seccion = :codigo_seccion, turno = :turno
                  WHERE id = :id";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindValue(':id_periodo', $seccion->getIdPeriodo(), PDO::PARAM_INT);
        $stmt->bindValue(':id_trayecto', $seccion->getIdTrayecto(), PDO::PARAM_INT);
        $stmt->bindValue(':codigo_seccion', $seccion->getCodigoSeccion(), PDO::PARAM_STR);
        $stmt->bindValue(':turno', $seccion->getTurno(), PDO::PARAM_STR);
        $stmt->bindValue(':id', $seccion->getId(), PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function validar_codigo_seccion(string $codigoSeccion, ?int $idExcluir = null): bool {
        $sql = "SELECT id FROM secciones WHERE codigo_seccion = :codigo_seccion";
        $params = [':codigo_seccion' => $codigoSeccion];
        if ($idExcluir !== null) {
            $sql .= " AND id != :id_excluir";
            $params[':id_excluir'] = $idExcluir;
        }
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute($params);
        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtener_secciones(): array {
        $query = "SELECT s.id, s.codigo_seccion, s.turno, p.nombre AS periodo, t.nombre AS trayecto
                  FROM secciones s
                  JOIN periodos_academicos p ON s.id_periodo = p.id
                  JOIN trayectos t ON s.id_trayecto = t.id
                  ORDER BY s.id DESC";
        $stmt = $this->conexion->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
