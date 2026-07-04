<?php

namespace Core\Config;

class DotEnv
{
    /**
     * Carga y procesa el archivo .env e inyecta las variables en el sistema.
     * En producción (Render), si el archivo físico no existe pero las variables
     * ya están en el sistema operativo, continúa sin arrojar error.
     */
    public static function cargar(string $rutaFisica): void
    {
        if (!file_exists($rutaFisica)) {
            // Evaluamos si ya existen variables clave configuradas en el entorno por Render.
            // Puedes cambiar 'DB_HOST' por cualquier variable obligatoria que uses para conectar tu BD.
            if (isset($_ENV['DB_HOST']) || isset($_SERVER['DB_HOST']) || getenv('DB_HOST') !== false) {
                return; // Estamos en producción con variables inyectadas nativamente. Todo bien.
            }

            throw new \RuntimeException("El archivo de entorno .env no existe en la ruta: {$rutaFisica}");
        }

        // Leer el archivo línea por línea eliminando saltos de línea extremos
        $lineas = file($rutaFisica, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lineas as $linea) {
            $linea = trim($linea);

            // Ignorar comentarios
            if (strpos($linea, '#') === 0) {
                continue;
            }

            // Validar que la línea tenga el formato CLAVE=VALOR
            if (strpos($linea, '=') !== false) {
                // Dividir solo por el primer signo '=' encontrado
                list($clave, $valor) = explode('=', $linea, 2);

                $clave = trim($clave);
                $valor = trim($valor);

                // Limpiar comillas si el valor viene envuelto en ellas (ej: "Centro de Estudiantes")
                $valor = trim($valor, '"\'');

                // Inyectar en los entornos globales de PHP si no han sido definidos previamente
                if (!array_key_exists($clave, $_SERVER) && !array_key_exists($clave, $_ENV)) {
                    putenv("{$clave}={$valor}");
                    $_ENV[$clave] = $valor;
                    $_SERVER[$clave] = $valor;
                }
            }
        }
    }
}