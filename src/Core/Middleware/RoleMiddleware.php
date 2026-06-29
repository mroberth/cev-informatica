<?php
declare(strict_types=1);

namespace Core\Middleware;

class RoleMiddleware{
    public static function procesar(string $rolRequerido): void{
        $payload = AuthMiddleware::getUsuarioPayload();

        if(!$payload){
            responder_error(401);
        }

        $user = $payload['user'] ?? null;
        $userRol = '';

        if(is_object($user)){
            $userRol = strtolower($user->rol ?? '');
        } elseif(is_array($user)){
            $userRol = strtolower($user['rol'] ?? '');
        }

        if($userRol !== strtolower($rolRequerido)){
            responder_error(403);
        }
    }
}
