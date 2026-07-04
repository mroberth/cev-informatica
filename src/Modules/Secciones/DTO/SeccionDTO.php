<?php
declare(strict_types=1);
namespace App\Secciones\DTO;

class SeccionDTO {
    public function __construct(
        private readonly int $id,
        private readonly int $idPeriodo,
        private readonly int $idTrayecto,
        private readonly string $codigoSeccion,
        private readonly string $turno
    ){}

    public function getId(): int { return $this->id; }
    public function getIdPeriodo(): int { return $this->idPeriodo; }
    public function getIdTrayecto(): int { return $this->idTrayecto; }
    public function getCodigoSeccion(): string { return $this->codigoSeccion; }
    public function getTurno(): string { return $this->turno; }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'id_periodo' => $this->idPeriodo,
            'id_trayecto' => $this->idTrayecto,
            'codigo_seccion' => $this->codigoSeccion,
            'turno' => $this->turno
        ];
    }
}
