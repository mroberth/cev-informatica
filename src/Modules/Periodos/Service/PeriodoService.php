<?php
declare(strict_types=1);
namespace App\Periodos\Service;

use App\Periodos\DTO\PeriodoDTO;
use Exception;

class PeriodoService {
    private const NOMBRE_REGEX = '/^\d{4}-(I|II)$/i';
    private const ESTADOS_VALIDOS = ['Activo', 'Inactivo'];

    public function validar(PeriodoDTO $periodo): PeriodoDTO {
        $this->validarNombre($periodo->getNombre());
        $this->validarFechaInicio($periodo->getFechaInicio());
        $this->validarFechaFin($periodo->getFechaFin(), $periodo->getFechaInicio());
        $this->validarEstado($periodo->getEstado());
        return $periodo;
    }

    private function validarNombre(string $nombre): void {
        $nombre = strtoupper(trim($nombre));

        if ($nombre === '') {
            throw new Exception('El nombre del período es obligatorio.', 400);
        }

        if (!preg_match(self::NOMBRE_REGEX, $nombre)) {
            throw new Exception('El formato debe ser AÑO-Semestre (ej: 2026-I, 2026-II).', 400);
        }
    }

    private function validarFechaInicio(string $fecha): void {
        if ($fecha === '') {
            throw new Exception('La fecha de inicio es obligatoria.', 400);
        }

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
            throw new Exception('Formato de fecha de inicio inválido.', 400);
        }
    }

    private function validarFechaFin(string $fecha, string $fechaInicio): void {
        if ($fecha === '') {
            throw new Exception('La fecha de fin es obligatoria.', 400);
        }

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
            throw new Exception('Formato de fecha de fin inválido.', 400);
        }

        if ($fecha <= $fechaInicio) {
            throw new Exception('La fecha de fin debe ser posterior a la fecha de inicio.', 400);
        }
    }

    private function validarEstado(string $estado): void {
        if ($estado === '') {
            throw new Exception('El estado es obligatorio.', 400);
        }

        if (!in_array($estado, self::ESTADOS_VALIDOS, true)) {
            throw new Exception('El estado seleccionado no es válido.', 400);
        }
    }
}
