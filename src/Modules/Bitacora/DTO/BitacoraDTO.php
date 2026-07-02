<?php
namespace App\Bitacora\DTO;

class BitacoraDTO {
    private ?int $id;
    private ?int $id_usuario;
    private string $accion;
    private string $descripcion;
    private string $direccion_ip;
    private ?string $user_agent;
    private ?string $creado_en;

    public function __construct(
        ?int $id,
        ?int $id_usuario,
        string $accion,
        string $descripcion,
        string $direccion_ip,
        ?string $user_agent,
        ?string $creado_en
    ) {
        $this->id = $id;
        $this->id_usuario = $id_usuario;
        $this->accion = $accion;
        $this->descripcion = $descripcion;
        $this->direccion_ip = $direccion_ip;
        $this->user_agent = $user_agent;
        $this->creado_en = $creado_en;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getIdUsuario(): ?int {
        return $this->id_usuario;
    }

    public function getAccion(): string {
        return $this->accion;
    }

    public function getDescripcion(): string {
        return $this->descripcion;
    }

    public function getDireccionIp(): string {
        return $this->direccion_ip;
    }

    public function getUserAgent(): ?string {
        return $this->user_agent;
    }

    public function getCreadoEn(): ?string {
        return $this->creado_en;
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'id_usuario' => $this->id_usuario,
            'accion' => $this->accion,
            'descripcion' => $this->descripcion,
            'direccion_ip' => $this->direccion_ip,
            'user_agent' => $this->user_agent,
            'creado_en' => $this->creado_en,
        ];
    }
}
