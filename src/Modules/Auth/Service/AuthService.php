<?php
declare(strict_types=1);

namespace App\Auth\Service;

use App\Auth\Repository\LoginRepository;
use App\Auth\Repository\RefreshTokenRepository;
use App\Auth\Repository\JwtBlacklistRepository;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use DateTimeImmutable;
use Exception;

class AuthService{
    public function __construct(
        private readonly LoginRepository $loginRepository,
        private readonly RefreshTokenRepository $refreshTokenRepository,
        private readonly JwtBlacklistRepository $jwtBlacklistRepository
    ){}

    public function iniciarSesion(string $correo, string $password): \TokenDTO{
        $usuario = $this->loginRepository->buscarPorCorreo($correo);

        if(!$usuario){
            throw new Exception("Correo o contraseña incorrectos", 401);
        }

        if(!password_verify($password, $usuario->getPasswordHash())){
            throw new Exception("Correo o contraseña incorrectos", 401);
        }

        if($usuario->getEstado() !== 'activo'){
            throw new Exception("Usuario inactivo", 403);
        }

        return $this->generarParDeTokens($usuario);
    }

    public function refrescarToken(string $rawRefreshToken): \TokenDTO{
        $tokenHash = hash('sha256', $rawRefreshToken);
        $refreshTokenDTO = $this->refreshTokenRepository->buscarPorHashDeToken($tokenHash);

        if(!$refreshTokenDTO){
            throw new Exception("Refresh token inválido", 401);
        }

        if($refreshTokenDTO->estaRevocado()){
            throw new Exception("Refresh token revocado", 401);
        }

        $ahora = new DateTimeImmutable();
        $expiracion = new DateTimeImmutable($refreshTokenDTO->getExpiracion());
        if($ahora > $expiracion){
            throw new Exception("Refresh token expirado", 401);
        }

        $usuario = $this->loginRepository->buscarPorId($refreshTokenDTO->getUsuarioId());
        if(!$usuario || $usuario->getEstado() !== 'activo'){
            throw new Exception("Usuario no encontrado o inactivo", 401);
        }

        $this->refreshTokenRepository->revocarPorHashDeToken($tokenHash);

        return $this->generarParDeTokens($usuario);
    }

    public function cerrarSesion(string $accessToken, string $rawRefreshToken): void{
        try{
            $parts = explode('.', $accessToken);
            if(count($parts) === 3){
                $payloadJson = base64_decode(str_replace(['-', '_'], ['+', '/'], $parts[1]));
                $payload = json_decode($payloadJson, true);
                $expiracionToken = $payload['exp'] ?? (time() + 900);

                $firma = $parts[2];
                $firmaHash = hash('sha256', $firma);
                $formatoExpiracion = date('Y-m-d H:i:s', $expiracionToken);

                $this->jwtBlacklistRepository->guardarEnListaNegra($firmaHash, $formatoExpiracion);
            }
        } catch(Exception $e){
            // Si falla al procesar la firma, continuamos para revocar el refresh token
        }

        $tokenHash = hash('sha256', $rawRefreshToken);
        $this->refreshTokenRepository->revocarPorHashDeToken($tokenHash);
    }

    private function generarParDeTokens(\UserDTO $usuario): \TokenDTO{
        $ahora = new DateTimeImmutable();
        $expiracionAccess = $ahora->modify('+15 minutes')->getTimestamp();
        $expiracionRefresh = $ahora->modify('+7 days');

        $payload = [
            'iss' => $_ENV['JWT_ISSUER'] ?? 'cev_informatica',
            'aud' => $_ENV['JWT_AUDIENCE'] ?? 'cev_frontend',
            'iat' => $ahora->getTimestamp(),
            'exp' => $expiracionAccess,
            'sub' => (string) $usuario->getID(),
            'user' => [
                'correo' => $usuario->getCorreo(),
                'rol' => $usuario->getNombreRol()
            ]
        ];

        $accessToken = JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');

        $rawRefreshToken = bin2hex(random_bytes(32));
        $hashedRefreshToken = hash('sha256', $rawRefreshToken);

        $this->refreshTokenRepository->guardarToken(
            $usuario->getID(),
            $hashedRefreshToken,
            $expiracionRefresh->format('Y-m-d H:i:s')
        );

        return new \TokenDTO($accessToken, $rawRefreshToken, 900, $usuario);
    }
}
