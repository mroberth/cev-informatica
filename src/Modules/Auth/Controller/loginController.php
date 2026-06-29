<?php

use App\Auth\Service\AuthService;
use App\Auth\Repository\LoginRepository;
use App\Auth\Repository\RefreshTokenRepository;
use App\Auth\Repository\JwtBlacklistRepository;

function mostrarLogin(): void{
    require_once BASE_PATH . '/src/views/login/login.php';
}

function iniciar_sesion(): void{
    header('Content-Type: application/json; charset=utf-8');

    $input = json_decode(file_get_contents('php://input'), true);
    $correo = filter_var($input['correo'] ?? '', FILTER_VALIDATE_EMAIL);
    $password = $input['password'] ?? '';

    if(!$correo || !$password){
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'error' => 'Credenciales inválidas o incompletas.'
        ], JSON_UNESCAPED_UNICODE);
        return;
    }

    try{
        $authService = new AuthService(
            new LoginRepository(),
            new RefreshTokenRepository(),
            new JwtBlacklistRepository()
        );

        $tokenDTO = $authService->iniciarSesion($correo, $password);

        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'data' => $tokenDTO->toArray()
        ], JSON_UNESCAPED_UNICODE);
    } catch(Exception $e){
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode([
            'status' => 'error',
            'error' => $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
    }
}

function refrescar_token(): void{
    header('Content-Type: application/json; charset=utf-8');

    $input = json_decode(file_get_contents('php://input'), true);
    $refreshToken = $input['refresh_token'] ?? '';

    if(!$refreshToken){
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'error' => 'Refresh token no proporcionado'
        ], JSON_UNESCAPED_UNICODE);
        return;
    }

    try{
        $authService = new AuthService(
            new LoginRepository(),
            new RefreshTokenRepository(),
            new JwtBlacklistRepository()
        );

        $tokenDTO = $authService->refrescarToken($refreshToken);

        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'data' => $tokenDTO->toArray()
        ], JSON_UNESCAPED_UNICODE);
    } catch(Exception $e){
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode([
            'status' => 'error',
            'error' => $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
    }
}

function cerrar_sesion(): void{
    header('Content-Type: application/json; charset=utf-8');

    $input = json_decode(file_get_contents('php://input'), true);
    $refreshToken = $input['refresh_token'] ?? '';

    $accessToken = '';
    $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';
    if(empty($authHeader) && function_exists('apache_request_headers')){
        $headers = apache_request_headers();
        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';
    }
    if(preg_match('/Bearer\s(\S+)/i', $authHeader, $matches)){
        $accessToken = $matches[1];
    } elseif(isset($_COOKIE['access_token'])){
        $accessToken = $_COOKIE['access_token'];
    }

    if(!$refreshToken){
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'error' => 'Refresh token no proporcionado'
        ], JSON_UNESCAPED_UNICODE);
        return;
    }

    try{
        $authService = new AuthService(
            new LoginRepository(),
            new RefreshTokenRepository(),
            new JwtBlacklistRepository()
        );

        $authService->cerrarSesion($accessToken, $refreshToken);

        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'data' => ['message' => 'Sesión cerrada exitosamente']
        ], JSON_UNESCAPED_UNICODE);
    } catch(Exception $e){
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode([
            'status' => 'error',
            'error' => $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
    }
}
