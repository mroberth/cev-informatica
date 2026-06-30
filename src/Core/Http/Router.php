<?php
declare(strict_types=1);
namespace Core\Http;

class Router {
    private static $rutas = [];
    private static $middlewaresAntes = [];
    private static $middlewaresDespues = [];
    private static $rutaNoEncontrada = null;
    private static $metodoNoPermitido = null;

    /**
     * Middleware que se ejecuta antes de la ruta
     */
    public static function antes($metodos, $patron, $manejador){
        self::$middlewaresAntes[] = [
            'metodos' => $metodos,
            'patron' => $patron,
            'manejador' => $manejador
        ];
    }

    private static function agregarRuta($metodo, $patron, $manejador){
        self::$rutas[] = [
            'metodo' => $metodo,
            'patron' => $patron,
            'manejador' => $manejador
        ];
    }

    public static function get($patron, $manejador){
        self::agregarRuta('GET', $patron, $manejador);
    }

    public static function post($patron, $manejador){
        self::agregarRuta('POST', $patron, $manejador);
    }

    public static function put($patron, $manejador){
        self::agregarRuta('PUT', $patron, $manejador);
    }

    public static function delete($patron, $manejador){
        self::agregarRuta('DELETE', $patron, $manejador);
    }

    public static function rutaNoEncontrada($manejador){
        self::$rutaNoEncontrada = $manejador;
    }

    public static function metodoNoPermitido($manejador){
        self::$metodoNoPermitido = $manejador;
    }

    public static function ejecutar(){
        //Obtener el metodo HTTP y la ruta solicitada
        $metodo = $_SERVER['REQUEST_METHOD'];
        $ruta = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        //Calcular ruta base del proyecto
        $rutaBase = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        $rutaRelativa = substr($ruta, strlen($rutaBase));

        //Limpiar y normalizar la ruta
        $rutaLimpia = trim($rutaRelativa, '/');

        //Ejecutar el middleware antes de procesar la ruta
        self::ejecutarMiddlewares(self::$middlewaresAntes, $metodo, $rutaLimpia);

        //Buscar ruta que coincida
        $rutaEncontrada = false;
        $metodoPermitido = false;

        foreach (self::$rutas as $ruta){
            //Verificar si el patron coincide con la ruta solicitada
            if($ruta['patron'] === $rutaLimpia || self::coincidePatron($ruta['patron'], $rutaLimpia)){
                $rutaEncontrada = true;

                //Verificar si el metodo coincide
                if($ruta['metodo'] === $metodo || $ruta['metodo'] === 'ALL'){
                    $metodoPermitido = true;
                    //Ejecutar el manejador de la ruta
                    call_user_func($ruta['manejador']);
                    return;
                }
            }
        }

        //Manejar casos de error
        if(!$rutaEncontrada && self::$rutaNoEncontrada){
            call_user_func(self::$rutaNoEncontrada);
        } elseif(!$metodoPermitido && self::$metodoNoPermitido){
            call_user_func(self::$metodoNoPermitido);
        } else{
            //Si no hay manejadores de error definidos, lanzar una excepción
            throw new \Exception("Ruta no encontrada o método no permitido", 404);
        }
    }

    private static function coincidePatron($patron, $ruta){
        if($patron === '*'){
            return true;
        }

        if(str_ends_with($patron, '/*')){
            $prefijo = rtrim(substr($patron, 0, -2), '/');

            if($ruta === $prefijo){
                return true;
            }

            return str_starts_with($ruta, $prefijo . '/');
        }

        //Convertir el patron en una expresión regular
        $expresion = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_-]+)', $patron);
        $expresion = str_replace('/', '\/', $expresion);
        return preg_match('/^' . $expresion . '$/', $ruta);
    }

    private static function ejecutarMiddlewares($middlewares, $metodo, $ruta){
        foreach ($middlewares as $middleware){
            $metodosMiddleware = $middleware['metodos'];
            $patronMiddleware = $middleware['patron'];

            //Verificar si el middleware aplica para este metodo y ruta
            if(($metodosMiddleware === 'ALL' || $metodosMiddleware === $metodo) &&
                ($patronMiddleware === '*' || $patronMiddleware === $ruta || self::coincidePatron($patronMiddleware, $ruta))){
                call_user_func($middleware['manejador']);
            }
        }
    }

}