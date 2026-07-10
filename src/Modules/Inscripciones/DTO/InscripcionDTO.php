<?php
declare(strict_types=1);
namespace App\Inscripciones\DTO;

class InscripcionDTO {
    public function __construct(
        private readonly int $id,
        private readonly int $idEstudiante,
        private readonly int $idSeccion,
        private readonly string $fechaInscripcion,
        private readonly string $estado
    ){}

    public function getId(): int { return $this->id; }
    public function getIdEstudiante(): int { return $this->idEstudiante; }
    public function getIdSeccion(): int { return $this->idSeccion; }
    public function getFechaInscripcion(): string { return $this->fechaInscripcion; }
    public function getEstado(): string { return $this->estado; }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'id_estudiante' => $this->idEstudiante,
            'id_seccion' => $this->idSeccion,
            'fecha_inscripcion' => $this->fechaInscripcion,
            'estado' => $this->estado,
        ];
    }
}
