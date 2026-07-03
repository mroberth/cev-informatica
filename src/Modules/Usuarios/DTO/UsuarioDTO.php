<?php
declare(strict_types= 1);
namespace App\Usuarios\DTO;

class UsuarioDTO {
    public function __construct(
        private readonly int $id,
        private readonly string $tipoCedula,
        private readonly string $cedula,
        private readonly string $nombre,
        private readonly string $apellido,
        private readonly string $correo,
        private readonly string $telefono,
        private readonly string $password,
        private readonly int $usuario_id,
        private readonly string $estado,
    ){}

    public function getId(): int { return $this->id; }
    public function getTipoCedula(): string { return $this->tipoCedula; }
    public function getCedula(): string { return $this->cedula; }
    public function getNombre(): string { return $this->nombre; }
    public function getApellido(): string { return $this->apellido; }
    public function getCorreo(): string { return $this->correo; }
    public function getTelefono(): string { return $this->telefono; }
    public function getPassword(): string { return $this->password; }
    public function getUsuarioId(): int { return $this->usuario_id; }
    public function getEstado(): string { return $this->estado; }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'tipo_cedula' => $this->tipoCedula,
            'cedula' => $this->cedula,
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'correo' => $this->correo,
            'telefono' => $this->telefono,
            'password' => $this->password,
            'usuario_id' => $this->usuario_id,
            'estado' => $this->estado
        ];
    }
}
