<?php
declare(strict_types=1);

namespace App\Materia\DTO;

class CalificacionDTO
{
    public function __construct(
        private readonly int $idEvaluacion,
        private readonly int $idEstudiante,
        private readonly ?float $nota = null,
        private readonly ?string $observaciones = null,
        private readonly int $id = 0,
    ) {}

    public function getId(): int { return $this->id; }
    public function getIdEvaluacion(): int { return $this->idEvaluacion; }
    public function getIdEstudiante(): int { return $this->idEstudiante; }
    public function getNota(): ?float { return $this->nota; }
    public function getObservaciones(): ?string { return $this->observaciones; }
}
