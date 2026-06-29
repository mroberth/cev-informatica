<?php
declare(strict_types=1);

namespace Core\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class GuestMiddleware{
    public static function procesar(): void{
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';

        if(empty($authHeader) && function_exists('apache_request_headers')){
            $headers = apache_request_headers();
            $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';
        }

        $token = '';
        if(preg_match('/Bearer\s(\S+)/i', $authHeader, $matches)){
            $token = $matches[1];
        }

        if(!$token){
            return;
        }

        try{
            $secret = $_ENV['JWT_SECRET'] ?? '';
            JWT::decode($token, new Key($secret, 'HS256'));

            header('Location: /a/dashboard');
            exit;
        } catch(Exception $e){
            return;
        }
    }
}
