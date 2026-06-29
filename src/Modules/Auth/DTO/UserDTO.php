<?php
declare(strict_types=1);

class UserDTO{
    public function __construct(
        private readonly int $id,
        private readonly string $nombre,
        private readonly string $apellido,
        private readonly string $correo,
        private readonly string $passwordHash,
        private readonly string $estado,
        private readonly string $nombreRol
    ){}

    public function getID(): int{
        return $this->id;
    }

    public function getNombre(): string{
        return $this->nombre;
    }

    public function getApellido(): string{
        return $this->apellido;
    }

    public function getCorreo(): string{
        return $this->correo;
    }

    public function getPasswordHash(): string{
        return $this->passwordHash;
    }

    public function getEstado(): string{
        return $this->estado;
    }

    public function getNombreRol(): string{
        return $this->nombreRol;
    }

    public function toArray(): array{
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'correo' => $this->correo,
            'estado' => $this->estado,
            'nombre_rol' => $this->nombreRol
        ];
    }
}
