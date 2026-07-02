<?php
namespace App\Bitacora\Service;

use App\Bitacora\DTO\BitacoraDTO;
use Exception;

class BitacoraService
{
    private const ACCIONES_VALIDAS = [
        'LOGIN_FALLIDO', 'LOGIN_EXITOSO', 'LOGOUT', 'REFRESH_TOKEN',
        'CREAR', 'ACTUALIZAR', 'ELIMINAR', 'DESACTIVAR', 'ACTIVAR',
        'CONSULTAR', 'EXPORTAR',
    ];

    public function validarYPreparar(array $datos): BitacoraDTO
    {
        $idUsuario = isset($datos['id_usuario']) && $datos['id_usuario'] !== null
            ? (int) $datos['id_usuario']
            : null;
        $accion = trim((string) ($datos['accion'] ?? ''));
        $descripcion = trim((string) ($datos['descripcion'] ?? ''));
        $direccionIp = trim((string) ($datos['direccion_ip'] ?? ''));
        $userAgent = isset($datos['user_agent'])
            ? trim((string) $datos['user_agent'])
            : null;

        $this->validarAccion($accion);
        $this->validarDescripcion($descripcion);
        $this->validarDireccionIp($direccionIp);
        $this->validarUserAgent($userAgent);

        return new BitacoraDTO(
            null,
            $idUsuario,
            strtoupper($accion),
            $descripcion,
            $direccionIp,
            $userAgent,
            null
        );
    }

    private function validarAccion(string $accion): void
    {
        if ($accion === '') {
            throw new Exception('La acción es obligatoria.', 400);
        }

        if (strlen($accion) > 100) {
            throw new Exception('La acción no puede exceder los 100 caracteres.', 400);
        }

        if (!in_array(strtoupper($accion), self::ACCIONES_VALIDAS, true)) {
            throw new Exception("La acción '{$accion}' no es válida.", 400);
        }
    }

    private function validarDescripcion(string $descripcion): void
    {
        if ($descripcion === '') {
            throw new Exception('La descripción es obligatoria.', 400);
        }
    }

    private function validarDireccionIp(string $direccionIp): void
    {
        if ($direccionIp === '') {
            throw new Exception('La dirección IP es obligatoria.', 400);
        }

        if (strlen($direccionIp) > 45) {
            throw new Exception('La dirección IP no puede exceder los 45 caracteres.', 400);
        }

        if (!filter_var($direccionIp, FILTER_VALIDATE_IP)) {
            throw new Exception('La dirección IP no tiene un formato válido.', 400);
        }
    }

    private function validarUserAgent(?string $userAgent): void
    {
        if ($userAgent !== null && $userAgent === '') {
            throw new Exception('El user agent no puede estar vacío si se proporciona.', 400);
        }
    }
}
