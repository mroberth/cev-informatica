<?php
declare(strict_types=1);

namespace Core\Middleware;

class RoleMiddleware{
    public static function procesar(string $rolRequerido): void{
        $payload = AuthMiddleware::getUsuarioPayload();

        if(!$payload){
            responder_error(401);
        }

        $user = null;
        if(is_array($payload)){
            $user = $payload['user'] ?? null;
        } elseif(is_object($payload)){
            $user = $payload->user ?? null;
        }
        $userRol = '';

        if(is_object($user)){
            $userRol = strtolower($user->rol ?? $user->nombre_rol ?? '');
        } elseif(is_array($user)){
            $userRol = strtolower($user['rol'] ?? $user['nombre_rol'] ?? '');
        }

        if($userRol !== strtolower($rolRequerido)){
            responder_error(403);
        }
    }
}
