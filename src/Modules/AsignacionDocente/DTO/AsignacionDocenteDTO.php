<?php
declare(strict_types=1);
namespace App\AsignacionDocente\DTO;

class AsignacionDocenteDTO {
    public function __construct(
        private readonly int $id,
        private readonly int $idSeccion,
        private readonly int $idDocente,
        private readonly int $idUnidadCurricular
    ){}

    public function getId(): int { return $this->id; }
    public function getIdSeccion(): int { return $this->idSeccion; }
    public function getIdDocente(): int { return $this->idDocente; }
    public function getIdUnidadCurricular(): int { return $this->idUnidadCurricular; }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'id_seccion' => $this->idSeccion,
            'id_docente' => $this->idDocente,
            'id_unidad_curricular' => $this->idUnidadCurricular,
        ];
    }
}
