<?php
declare(strict_types=1);
namespace App\Periodos\DTO;

class PeriodoDTO {
    public function __construct(
        private readonly int $id,
        private readonly string $nombre,
        private readonly string $fechaInicio,
        private readonly string $fechaFin,
        private readonly string $estado
    ){}

    public function getId(): int { return $this->id; }
    public function getNombre(): string { return $this->nombre; }
    public function getFechaInicio(): string { return $this->fechaInicio; }
    public function getFechaFin(): string { return $this->fechaFin; }
    public function getEstado(): string { return $this->estado; }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'fecha_inicio' => $this->fechaInicio,
            'fecha_fin' => $this->fechaFin,
            'estado' => $this->estado,
        ];
    }
}
