<?php
declare(strict_types=1);
namespace App\Secciones\Service;

use App\Secciones\DTO\SeccionDTO;
use Exception;

class SeccionService {
    private const CODIGO_REGEX = '/^IN-[1-4]1\d{2}$/';
    private const TURNOS_VALIDOS = ['Diurno'];

    public function validar(SeccionDTO $seccion): SeccionDTO {
        $this->validarIdPeriodo($seccion->getIdPeriodo());
        $this->validarIdTrayecto($seccion->getIdTrayecto());
        $this->validarCodigoSeccion($seccion->getCodigoSeccion(), $seccion->getIdTrayecto());
        $this->validarTurno($seccion->getTurno());
        return $seccion;
    }

    private function validarIdPeriodo(int $idPeriodo): void {
        if ($idPeriodo <= 0) {
            throw new Exception('El período académico es obligatorio.', 400);
        }
    }

    private function validarIdTrayecto(int $idTrayecto): void {
        if ($idTrayecto <= 0) {
            throw new Exception('El trayecto es obligatorio.', 400);
        }
    }

    private function validarCodigoSeccion(string $codigo, int $idTrayecto): void {
        $codigo = strtoupper(trim($codigo));

        if ($codigo === '') {
            throw new Exception('El código de sección es obligatorio.', 400);
        }

        if (!preg_match(self::CODIGO_REGEX, $codigo)) {
            throw new Exception('El formato debe ser IN-X1YY (ej: IN-1101, IN-2101).', 400);
        }

        $digitoCodigo = $codigo[3] ?? '';
        $digitoTrayecto = (string) $idTrayecto;

        if ($digitoCodigo !== $digitoTrayecto) {
            throw new Exception("El código debe iniciar con IN-{$digitoTrayecto} para coincidir con el Trayecto seleccionado.", 400);
        }
    }

    private function validarTurno(string $turno): void {
        if ($turno === '') {
            throw new Exception('El turno es obligatorio.', 400);
        }

        if (!in_array($turno, self::TURNOS_VALIDOS, true)) {
            throw new Exception('El turno seleccionado no es válido.', 400);
        }
    }
}
