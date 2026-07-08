<?php
declare(strict_types=1);
namespace App\Estudiantes\DTO;

class EstudianteDTO {
    public function __construct(
        private readonly int $id,
        private readonly int $idUsuario,
        private readonly string $estadoAcademico
    ){}

    public function getId(): int { return $this->id; }
    public function getIdUsuario(): int { return $this->idUsuario; }
    public function getEstadoAcademico(): string { return $this->estadoAcademico; }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'id_usuario' => $this->idUsuario,
            'estado_academico' => $this->estadoAcademico,
        ];
    }
}
