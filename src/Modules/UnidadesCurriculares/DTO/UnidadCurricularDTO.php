<?php
declare(strict_types=1);
namespace App\UnidadesCurriculares\DTO;

class UnidadCurricularDTO {
    public function __construct(
        private readonly int $id,
        private readonly int $idFase,
        private readonly string $codigo,
        private readonly string $nombre,
        private readonly int $unidadesCredito,
    ){}

    public function getId(): int { return $this->id; }
    public function getIdFase(): int { return $this->idFase; }
    public function getCodigo(): string { return $this->codigo; }
    public function getNombre(): string { return $this->nombre; }
    public function getUnidadesCredito(): int { return $this->unidadesCredito; }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'id_fase' => $this->idFase,
            'codigo' => $this->codigo,
            'nombre' => $this->nombre,
            'unidades_credito' => $this->unidadesCredito,
        ];
    }
}
