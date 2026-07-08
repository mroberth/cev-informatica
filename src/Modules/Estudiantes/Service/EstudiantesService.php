<?php
declare(strict_types=1);
namespace App\Estudiantes\Service;

use App\Estudiantes\DTO\EstudianteDTO;
use Exception;

class EstudiantesService {
    private const ESTADOS_VALIDOS = ['Activo', 'Egresado', 'Retirado'];

    public function validar(EstudianteDTO $estudiante): EstudianteDTO {
        $this->validarIdUsuario($estudiante->getIdUsuario());
        $this->validarEstadoAcademico($estudiante->getEstadoAcademico());
        return $estudiante;
    }

    public function validarActualizar(EstudianteDTO $estudiante): EstudianteDTO {
        $this->validarEstadoAcademico($estudiante->getEstadoAcademico());
        return $estudiante;
    }

    private function validarIdUsuario(int $idUsuario): void {
        if ($idUsuario <= 0) {
            throw new Exception('Debe seleccionar un usuario.', 400);
        }
    }

    private function validarEstadoAcademico(string $estado): void {
        if ($estado === '') {
            throw new Exception('El estado académico es obligatorio.', 400);
        }
        if (!in_array($estado, self::ESTADOS_VALIDOS, true)) {
            throw new Exception('El estado académico seleccionado no es válido.', 400);
        }
    }
}
