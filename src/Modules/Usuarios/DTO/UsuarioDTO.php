<?php
declare(strict_types= 1);
namespace App\Usuarios\DTO;

class UsuarioDTO {
    public function __construct(
        private readonly int $id,
        private readonly string $nombre,
        private readonly string $apellido,
        private readonly string $correo,
        private readonly string $password,
        private readonly int $usuario_id,
        private readonly string $estado,
    ){}

    public function getId(): int {
        return $this->id;
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    public function getApellido(): string {
        return $this->apellido;
    }

    public function getCorreo(): string {
        return $this->correo;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function getUsuarioId(): int {
        return $this->usuario_id;
    }

    public function getEstado(): string {
        return $this->estado;
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'correo' => $this->correo,
            'password' => $this->password,
            'usuario_id' => $this->usuario_id,
            'estado' => $this->estado
        ];
    }
}