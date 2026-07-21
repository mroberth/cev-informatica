<?php
declare(strict_types=1);

namespace Core\Middleware;

use Core\Database\Conexion;
use PDO;

class RateLimiter
{
    private const CLEANUP_PROBABILITY = 0.01;

    private static array $config = [
        'default'       => ['max_tokens' => 60, 'seconds' => 60],
        'iniciar_sesion' => ['max_tokens' => 5,  'seconds' => 60],
        'refresh'       => ['max_tokens' => 10, 'seconds' => 60],
    ];

    public static function procesar(): void
    {
        $endpoint = self::obtenerEndpoint();
        $config = self::$config[$endpoint] ?? self::$config['default'];

        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $maxTokens = $config['max_tokens'];
        $seconds = $config['seconds'];

        $db = Conexion::obtenerConexion('security');

        $now = time();
        $refillPerSecond = $maxTokens / $seconds;

        $db->beginTransaction();
        try {
            $stmt = $db->prepare(
                "SELECT tokens_actuales, ultima_peticion
                 FROM rate_limits
                 WHERE ip_address = :ip AND endpoint = :endpoint
                 FOR UPDATE"
            );
            $stmt->bindValue(':ip', $ip, PDO::PARAM_STR);
            $stmt->bindValue(':endpoint', $endpoint, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $elapsed = $now - (int) $row['ultima_peticion'];
                $tokens = (float) $row['tokens_actuales'] + $elapsed * $refillPerSecond;
                if ($tokens > $maxTokens) {
                    $tokens = (float) $maxTokens;
                }
            } else {
                $tokens = (float) $maxTokens;
            }

            if ($tokens < 1.0) {
                $db->rollBack();
                $retryAfter = (int) ceil((1.0 - $tokens) / $refillPerSecond);
                header('Retry-After: ' . $retryAfter);
                responder_error(429);
            }

            $tokens -= 1.0;

            $upsert = "INSERT INTO rate_limits (ip_address, endpoint, tokens_actuales, ultima_peticion)
                       VALUES (:ip, :endpoint, :tokens, :now)
                       ON DUPLICATE KEY UPDATE
                         tokens_actuales = VALUES(tokens_actuales),
                         ultima_peticion = VALUES(ultima_peticion)";
            $stmt = $db->prepare($upsert);
            $stmt->bindValue(':ip', $ip, PDO::PARAM_STR);
            $stmt->bindValue(':endpoint', $endpoint, PDO::PARAM_STR);
            $stmt->bindValue(':tokens', $tokens);
            $stmt->bindValue(':now', $now, PDO::PARAM_INT);
            $stmt->execute();

            $db->commit();
        } catch (\Exception $e) {
            $db->rollBack();
            return;
        }

        if (mt_rand(1, 10000) <= self::CLEANUP_PROBABILITY * 10000) {
            self::limpiar($db);
        }
    }

    private static function obtenerEndpoint(): string
    {
        $rutaBase = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $ruta = trim(substr($uri, strlen($rutaBase)), '/');
        return $ruta === '' ? '/' : $ruta;
    }

    private static function limpiar(PDO $db): void
    {
        $db->exec(
            "DELETE FROM rate_limits
             WHERE ultima_peticion < UNIX_TIMESTAMP() - 86400"
        );
    }
}
