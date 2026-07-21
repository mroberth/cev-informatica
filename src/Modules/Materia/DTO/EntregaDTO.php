<?php
declare(strict_types=1);

namespace App\Materia\DTO;

class EntregaDTO
{
    public function __construct(
        private readonly int $idEvaluacion,
        private readonly int $idEstudiante,
        private readonly string $archivoRuta,
        private readonly string $archivoNombreOriginal,
        private readonly ?string $comentarioAlumno = null,
        private readonly int $id = 0,
    ) {}

    public function getId(): int { return $this->id; }
    public function getIdEvaluacion(): int { return $this->idEvaluacion; }
    public function getIdEstudiante(): int { return $this->idEstudiante; }
    public function getArchivoRuta(): string { return $this->archivoRuta; }
    public function getArchivoNombreOriginal(): string { return $this->archivoNombreOriginal; }
    public function getComentarioAlumno(): ?string { return $this->comentarioAlumno; }
}
