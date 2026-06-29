<?php
declare(strict_types=1);

namespace Core\Middleware;

class RoleMiddleware{
    public static function procesar(string $rolRequerido): void{
        $payload = AuthMiddleware::getUsuarioPayload();

        if(!$payload){
            responder_error(401);
        }

        $userRol = strtolower($payload['user']['rol'] ?? '');

        if($userRol !== strtolower($rolRequerido)){
            responder_error(403);
        }
    }
}
