<?php
declare(strict_types=1);

namespace Core\Middleware;

use App\Auth\Repository\JwtBlacklistRepository;
use App\Auth\Repository\LoginRepository;
use App\Auth\Repository\RefreshTokenRepository;
use App\Auth\Service\AuthService;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class AuthMiddleware{
    private static ?array $usuarioPayload = null;

    public static function procesar(): void{
        $rutaRelativa = trim(
            substr(
                parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),
                strlen(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])))
            ),
            '/'
        );

        $rutasPublicas = ['', 'login', 'iniciar_sesion', 'refresh'];
        if(in_array($rutaRelativa, $rutasPublicas, true)){
            return;
        }

        $token = self::extraerToken();

        if($token !== null && self::validarToken($token)){
            return;
        }

        if(self::intentarRenovarConRefreshToken()){
            return;
        }

        responder_error(401);
    }

    public static function getUsuarioPayload(): ?array{
        return self::$usuarioPayload;
    }

    private static function validarToken(string $token): bool{
        $parts = explode('.', $token);
        if(count($parts) !== 3){
            return false;
        }

        $firmaHash = hash('sha256', $parts[2]);

        try{
            $blacklistRepo = new JwtBlacklistRepository();
            if($blacklistRepo->estaEnListaNegra($firmaHash)){
                return false;
            }
        } catch(Exception $e){
            // Si la tabla no existe o hay error de BD, asumimos que no está en lista negra
        }

        try{
            $secret = $_ENV['JWT_SECRET'] ?? '';
            $decoded = JWT::decode($token, new Key($secret, 'HS256'));
            $decodedArray = (array) $decoded;

            $expectedIssuer = $_ENV['JWT_ISSUER'] ?? 'cev_informatica';
            if(($decodedArray['iss'] ?? '') !== $expectedIssuer){
                return false;
            }

            self::$usuarioPayload = $decodedArray;

            return true;
        } catch(Exception $e){
            return false;
        }
    }

    private static function intentarRenovarConRefreshToken(): bool{
        $refreshToken = $_COOKIE['refresh_token'] ?? '';
        if($refreshToken === ''){
            return false;
        }

        try{
            $authService = new AuthService(
                new LoginRepository(),
                new RefreshTokenRepository(),
                new JwtBlacklistRepository()
            );

            $tokenDTO = $authService->refrescarToken($refreshToken);
            self::guardarCookiesSesion($tokenDTO->getAccessToken(), $tokenDTO->getRefreshToken());

            $secret = $_ENV['JWT_SECRET'] ?? '';
            $decoded = JWT::decode($tokenDTO->getAccessToken(), new Key($secret, 'HS256'));
            self::$usuarioPayload = (array) $decoded;

            return true;
        } catch(Exception $e){
            return false;
        }
    }

    private static function extraerToken(): ?string{
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';

        if(empty($authHeader) && function_exists('apache_request_headers')){
            $headers = apache_request_headers();
            $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';
        }

        if(preg_match('/Bearer\s(\S+)/i', $authHeader, $matches)){
            return $matches[1];
        }

        if(isset($_COOKIE['access_token'])){
            return $_COOKIE['access_token'];
        }

        return null;
    }

    private static function guardarCookieAcceso(string $token): void{
        setcookie('access_token', $token, [
            'expires' => time() + 900,
            'path' => '/',
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
    }

    private static function guardarCookiesSesion(string $accessToken, string $refreshToken): void{
        self::guardarCookieAcceso($accessToken);
        setcookie('refresh_token', $refreshToken, [
            'expires' => time() + 604800,
            'path' => '/',
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
    }
}
