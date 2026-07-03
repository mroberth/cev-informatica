<?php
declare(strict_types=1);
namespace App\Configuracion\Service;

use App\Configuracion\DTO\RolDTO;
use App\Configuracion\DTO\ModuloDTO;
use App\Configuracion\DTO\PermisoDTO;
use Exception;

class ConfigService {
    private const REGEX = '/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/';

    public function validarRol(string $nombreRol, ?string $descripcion): RolDTO {
        $nombreRol = trim($nombreRol);
        $this->validarNombre($nombreRol, 'Rol', 3, 50);
        $this->validarDescripcion($descripcion, 255);

        return new RolDTO(0, $nombreRol, $this->sanitizarDescripcion($descripcion));
    }

    public function validarModulo(string $nombre, ?string $descripcion): ModuloDTO {
        $nombre = trim($nombre);
        $this->validarNombre($nombre, 'Módulo', 3, 50);
        $this->validarDescripcion($descripcion, 255);

        return new ModuloDTO(0, $nombre, $this->sanitizarDescripcion($descripcion));
    }

    public function validarPermiso(string $nombre, ?string $descripcion): PermisoDTO {
        $nombre = trim($nombre);
        $this->validarNombre($nombre, 'Permiso', 3, 20);
        $this->validarDescripcion($descripcion, 100);

        return new PermisoDTO(0, $nombre, $this->sanitizarDescripcion($descripcion));
    }

    public function validarRolExistente(int $id, string $nombreRol, ?string $descripcion): RolDTO {
        if ($id <= 0) throw new Exception('ID de rol inválido.', 400);
        $nombreRol = trim($nombreRol);
        $this->validarNombre($nombreRol, 'Rol', 3, 50);
        $this->validarDescripcion($descripcion, 255);

        return new RolDTO($id, $nombreRol, $this->sanitizarDescripcion($descripcion));
    }

    public function validarModuloExistente(int $id, string $nombre, ?string $descripcion): ModuloDTO {
        if ($id <= 0) throw new Exception('ID de módulo inválido.', 400);
        $nombre = trim($nombre);
        $this->validarNombre($nombre, 'Módulo', 3, 50);
        $this->validarDescripcion($descripcion, 255);

        return new ModuloDTO($id, $nombre, $this->sanitizarDescripcion($descripcion));
    }

    public function validarPermisoExistente(int $id, string $nombre, ?string $descripcion): PermisoDTO {
        if ($id <= 0) throw new Exception('ID de permiso inválido.', 400);
        $nombre = trim($nombre);
        $this->validarNombre($nombre, 'Permiso', 3, 20);
        $this->validarDescripcion($descripcion, 100);

        return new PermisoDTO($id, $nombre, $this->sanitizarDescripcion($descripcion));
    }

    private function validarNombre(string $nombre, string $etiqueta, int $min, int $max): void {
        if ($nombre === '') {
            throw new Exception("El nombre del {$etiqueta} es obligatorio.", 400);
        }
        if (strlen($nombre) < $min) {
            throw new Exception("El nombre del {$etiqueta} debe tener al menos {$min} caracteres.", 400);
        }
        if (strlen($nombre) > $max) {
            throw new Exception("El nombre del {$etiqueta} no puede exceder los {$max} caracteres.", 400);
        }
        if (!preg_match(self::REGEX, $nombre)) {
            throw new Exception("El nombre del {$etiqueta} contiene caracteres no válidos.", 400);
        }
    }

    private function validarDescripcion(?string $descripcion, int $max): void {
        if ($descripcion !== null && $descripcion !== '') {
            $desc = trim($descripcion);
            if (strlen($desc) > $max) {
                throw new Exception("La descripción no puede exceder los {$max} caracteres.", 400);
            }
        }
    }

    private function sanitizarDescripcion(?string $descripcion): ?string {
        $d = $descripcion !== null ? trim($descripcion) : '';
        return $d === '' ? null : $d;
    }
}
