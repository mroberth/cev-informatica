<?php
declare(strict_types=1);

namespace App\Materia\DTO;

class EvaluacionDTO
{
    public function __construct(
        private readonly int $id,
        private readonly int $idAsignacionDocente,
        private readonly string $titulo,
        private readonly ?string $descripcion,
        private readonly string $tipo,
        private readonly ?float $porcentaje,
        private readonly string $fechaEntrega,
        private readonly int $creadoPor,
        private readonly ?string $creadoEn = null,
    ) {}

    public function getId(): int { return $this->id; }
    public function getIdAsignacionDocente(): int { return $this->idAsignacionDocente; }
    public function getTitulo(): string { return $this->titulo; }
    public function getDescripcion(): ?string { return $this->descripcion; }
    public function getTipo(): string { return $this->tipo; }
    public function getPorcentaje(): ?float { return $this->porcentaje; }
    public function getFechaEntrega(): string { return $this->fechaEntrega; }
    public function getCreadoPor(): int { return $this->creadoPor; }
    public function getCreadoEn(): ?string { return $this->creadoEn; }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'id_asignacion_docente' => $this->idAsignacionDocente,
            'titulo' => $this->titulo,
            'descripcion' => $this->descripcion,
            'tipo' => $this->tipo,
            'porcentaje' => $this->porcentaje,
            'fecha_entrega' => $this->fechaEntrega,
            'creado_por' => $this->creadoPor,
            'creado_en' => $this->creadoEn,
        ];
    }
}
