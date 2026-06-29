<?php
declare(strict_types=1);

class RefreshTokenDTO{
    public function __construct(
        private readonly int $id,
        private readonly int $usuarioId,
        private readonly string $tokenHash,
        private readonly string $expiracion,
        private readonly bool $revocado,
        private readonly string $creadoEn
    ){}

    public function getId(): int{
        return $this->id;
    }

    public function getUsuarioId(): int{
        return $this->usuarioId;
    }

    public function getTokenHash(): string{
        return $this->tokenHash;
    }

    public function getExpiracion(): string{
        return $this->expiracion;
    }

    public function estaRevocado(): bool{
        return $this->revocado;
    }

    public function getCreadoEn(): string{
        return $this->creadoEn;
    }

    public function toArray(): array{
        return [
            'id' => $this->id,
            'usuario_id' => $this->usuarioId,
            'token_hash' => $this->tokenHash,
            'expiracion' => $this->expiracion,
            'revocado' => $this->revocado,
            'creado_en' => $this->creadoEn
        ];
    }
}
