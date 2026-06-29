<?php
declare(strict_types= 1);

class UserDTO{
    public function __construct(
        private readonly int $id,
        private readonly string $nombre,
        private readonly string $apellido,
        private readonly string $correo,
        private readonly string $password,
        private readonly int $id_rol,
        private readonly string $estado
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

    public function getPassword(): string{
        return $this->password;
    }

    public function getIdRol(): int{
        return $this->id_rol;
    }

    public function getEstado(): string{
        return $this->estado;
    }
}