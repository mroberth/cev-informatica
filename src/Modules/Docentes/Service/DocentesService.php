<?php
declare(strict_types=1);
namespace App\Docentes\Service;

use App\Docentes\DTO\DocenteDTO;
use Exception;

class DocentesService {
    private const ESTADOS_VALIDOS = ['Activo', 'Inactivo'];

    public function validar(DocenteDTO $docente): DocenteDTO {
        $this->validarIdUsuario($docente->getIdUsuario());
        $this->validarEspecialidad($docente->getEspecialidad());
        $this->validarEstado($docente->getEstado());
        return $docente;
    }

    public function validarActualizar(DocenteDTO $docente): DocenteDTO {
        $this->validarEspecialidad($docente->getEspecialidad());
        $this->validarEstado($docente->getEstado());
        return $docente;
    }

    private function validarIdUsuario(int $idUsuario): void {
        if ($idUsuario <= 0) {
            throw new Exception('Debe seleccionar un usuario.', 400);
        }
    }

    private function validarEspecialidad(string $especialidad): void {
        if (strlen($especialidad) > 100) {
            throw new Exception('La especialidad no debe exceder 100 caracteres.', 400);
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
