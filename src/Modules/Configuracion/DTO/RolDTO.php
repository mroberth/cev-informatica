<?php
declare(strict_types=1);
namespace App\Configuracion\DTO;

class RolDTO {
    private int $id;
    private string $nombreRol;
    private ?string $descripcion;

    public function __construct(int $id, string $nombreRol, ?string $descripcion) {
        $this->id = $id;
        $this->nombreRol = $nombreRol;
        $this->descripcion = $descripcion;
    }

    public function getId(): int { return $this->id; }
    public function getNombreRol(): string { return $this->nombreRol; }
    public function getDescripcion(): ?string { return $this->descripcion; }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'nombre_rol' => $this->nombreRol,
            'descripcion' => $this->descripcion,
        ];
    }
}
