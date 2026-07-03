<?php
declare(strict_types=1);
namespace App\UnidadesCurriculares\Service;

use App\UnidadesCurriculares\DTO\UnidadCurricularDTO;
use Exception;

class UnidadCurricularService {
    private const CODIGO_REGEX = '/^[A-Za-z0-9\-]+$/';

    public function validar(UnidadCurricularDTO $uc): UnidadCurricularDTO {
        $this->validarIdFase($uc->getIdFase());
        $this->validarCodigo($uc->getCodigo());
        $this->validarNombre($uc->getNombre());
        $this->validarUnidadesCredito($uc->getUnidadesCredito());
        return $uc;
    }

    private function validarIdFase(int $idFase): void {
        if ($idFase <= 0) {
            throw new Exception('La fase es obligatoria.', 400);
        }
    }

    private function validarCodigo(string $codigo): void {
        if ($codigo === '') {
            throw new Exception('El código es obligatorio.', 400);
        }
        if (strlen($codigo) > 20) {
            throw new Exception('El código no debe exceder 20 caracteres.', 400);
        }
        if (!preg_match(self::CODIGO_REGEX, $codigo)) {
            throw new Exception('El código contiene caracteres no válidos.', 400);
        }
    }

    private function validarNombre(string $nombre): void {
        if ($nombre === '') {
            throw new Exception('El nombre es obligatorio.', 400);
        }
        if (strlen($nombre) > 100) {
            throw new Exception('El nombre no debe exceder 100 caracteres.', 400);
        }
        if (strlen($nombre) < 3) {
            throw new Exception('El nombre debe tener al menos 3 caracteres.', 400);
        }
    }

    private function validarUnidadesCredito(int $uc): void {
        if ($uc <= 0) {
            throw new Exception('Las unidades de crédito deben ser un valor positivo.', 400);
        }
        if ($uc > 20) {
            throw new Exception('Las unidades de crédito no pueden exceder 20.', 400);
        }
    }
}
