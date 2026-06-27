<?php
namespace Core\Exception;
use Throwable;

class ManejadorErrorGlobal {
    public static function registrar(){
        set_exception_handler([self::class, 'manejarExcepcion']);
    }

    public static function manejarExcepcion(Throwable $exception){
        ob_get_clean();
        $codigoHttp = $exception->getCode();
        if ($codigoHttp >= 400 && $codigoHttp < 600) {
            $codigoHttp = $codigoHttp;
        } else{
            $codigoHttp = 500;
        }

        header('Content-Type: application/json; charset=utf-8');
        http_response_code($codigoHttp);
        $response = [
            'estado' => $codigoHttp,
            'titulo' => $codigoHttp === 500 ? 'Error Interno del Servidor' : 'Error en la petición',
            'detalle' => $exception->getMessage()
        ];

        echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
}