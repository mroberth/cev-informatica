<?php
declare(strict_types=1);

namespace Core\Middleware;

class CsrfMiddleware
{
    private static array $publicRoutes = ['', 'login', 'iniciar_sesion', 'refresh'];

    public static function procesar(): void
    {
        $route = self::obtenerRuta();

        if (in_array($route, self::$publicRoutes, true)) {
            return;
        }

        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        if (in_array($method, ['GET', 'HEAD', 'OPTIONS'], true)) {
            if (!isset($_COOKIE['csrf_token'])) {
                self::generarToken();
            }
            return;
        }

        $headerToken = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        $cookieToken = $_COOKIE['csrf_token'] ?? '';

        if ($headerToken === '' || $cookieToken === '') {
            responder_error(419);
        }

        if (!hash_equals($cookieToken, $headerToken)) {
            responder_error(419);
        }
    }

    private static function obtenerRuta(): string
    {
        $rutaBase = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        return trim(substr($uri, strlen($rutaBase)), '/');
    }

    private static function generarToken(): void
    {
        $token = bin2hex(random_bytes(32));
        setcookie('csrf_token', $token, [
            'expires' => 0,
            'path' => '/',
            'httponly' => false,
            'samesite' => 'Lax',
        ]);
        $_COOKIE['csrf_token'] = $token;
    }
}
