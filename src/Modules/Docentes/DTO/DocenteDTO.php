<?php
declare(strict_types=1);
namespace App\Docentes\DTO;

class DocenteDTO {
    public function __construct(
        private readonly int $id,
        private readonly int $idUsuario,
        private readonly string $especialidad,
        private readonly string $estado
    ){}

    public function getId(): int { return $this->id; }
    public function getIdUsuario(): int { return $this->idUsuario; }
    public function getEspecialidad(): string { return $this->especialidad; }
    public function getEstado(): string { return $this->estado; }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'id_usuario' => $this->idUsuario,
            'especialidad' => $this->especialidad,
            'estado' => $this->estado,
        ];
    }
}
