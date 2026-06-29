<?php
declare(strict_types=1);

namespace Core\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Auth\Repository\JwtBlacklistRepository;
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

        if(!$token){
            responder_error(401);
        }

        $parts = explode('.', $token);
        if(count($parts) !== 3){
            responder_error(401);
        }

        $firmaHash = hash('sha256', $parts[2]);

        try{
            $blacklistRepo = new JwtBlacklistRepository();
            if($blacklistRepo->estaEnListaNegra($firmaHash)){
                responder_error(403);
            }
        } catch(Exception $e){
            responder_error(500);
        }

        try{
            $secret = $_ENV['JWT_SECRET'] ?? '';
            $decoded = JWT::decode($token, new Key($secret, 'HS256'));
            $decodedArray = (array) $decoded;

            $expectedIssuer = $_ENV['JWT_ISSUER'] ?? 'cev_informatica';
            if(($decodedArray['iss'] ?? '') !== $expectedIssuer){
                responder_error(401);
            }

            self::$usuarioPayload = $decodedArray;
        } catch(Exception $e){
            responder_error(401);
        }
    }

    public static function getUsuarioPayload(): ?array{
        return self::$usuarioPayload;
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
}
