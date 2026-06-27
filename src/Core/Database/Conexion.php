<?php

namespace Core\Database;

use PDO;
use PDOException;

class Conexion
{
    // Array asociativo para almacenar las diferentes conexiones ('security' o 'business')
    private static array $instancias = [];

    /**
     * Retorna la instancia de conexión PDO específica según el tipo solicitado.
     * * @param string $tipo Puede ser 'security' o 'business'
     */
    public static function obtenerConexion(string $tipo = 'business'): PDO
    {
        // Validar que el tipo solicitado sea correcto
        if ($tipo !== 'security' && $tipo !== 'business') {
            throw new \InvalidArgumentException("Tipo de conexión no válido: {$tipo}");
        }

        // Si la conexión solicitada ya existe en memoria, la reutilizamos
        if (isset(self::$instancias[$tipo])) {
            return self::$instancias[$tipo];
        }

        try {
            // Recuperamos las variables comunes del .env
            $host    = $_ENV['DB_HOST'] ?? '127.0.0.1';
            $port    = $_ENV['DB_PORT'] ?? '3306';
            $user    = $_ENV['DB_USER'] ?? '';
            $pass    = $_ENV['DB_PASS'] ?? '';
            $charset = $_ENV['DB_CHARSET'] ?? 'utf8mb4';

            // Seleccionamos dinámicamente el nombre de la base de datos según el tipo
            $db = ($tipo === 'security') 
                ? ($_ENV['DB_SECURITY'] ?? '') 
                : ($_ENV['DB_BUSINESS'] ?? '');

            if (empty($db)) {
                throw new \RuntimeException("El nombre de la base de datos para '{$tipo}' no está definido en el archivo .env");
            }

            // Construimos el DSN
            $dsn = "mysql:host={$host};port={$port};dbname={$db};charset={$charset}";

            $opciones = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            // Almacenamos la nueva conexión en el array bajo su clave correspondiente
            self::$instancias[$tipo] = new PDO($dsn, $user, $pass, $opciones);

            return self::$instancias[$tipo];

        } catch (PDOException $e) {
            throw new \RuntimeException("Fallo de conexión a la Base de Datos ({$tipo}): " . $e->getMessage());
        }
    }
}