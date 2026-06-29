<?php
declare(strict_types=1);

class TokenDTO{
    public function __construct(
        private readonly string $accessToken,
        private readonly string $refreshToken,
        private readonly int $expiraEn,
        private readonly UserDTO $usuario
    ){}

    public function getAccessToken(): string{
        return $this->accessToken;
    }

    public function getRefreshToken(): string{
        return $this->refreshToken;
    }

    public function getExpiraEn(): int{
        return $this->expiraEn;
    }

    public function getUsuario(): UserDTO{
        return $this->usuario;
    }

    public function toArray(): array{
        return [
            'access_token' => $this->accessToken,
            'refresh_token' => $this->refreshToken,
            'expires_in' => $this->expiraEn,
            'user' => $this->usuario->toArray()
        ];
    }
}
