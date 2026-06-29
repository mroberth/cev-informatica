<?php
namespace Core\Exception;
use Throwable;

class ManejadorErrorGlobal {
    public static function registrar(){
        set_exception_handler([self::class, 'manejarExcepcion']);
    }

    public static function manejarExcepcion(Throwable $exception){
        ob_get_clean();
        error_log("[CEV-ERROR] " . $exception->getMessage() . " en " . $exception->getFile() . ":" . $exception->getLine());
        $codigo = $exception->getCode();
        if ($codigo < 400 || $codigo >= 600) {
            $codigo = 500;
        }
        responder_error($codigo);
    }
}
