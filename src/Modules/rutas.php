<?php

use Core\Http\Router;
use Core\Middleware\AuthMiddleware;
use Core\Middleware\RoleMiddleware;
use Core\Middleware\RateLimiter;
use Core\Middleware\CsrfMiddleware;

// ==================== MIDDLEWARE GLOBAL ====================
Router::antes('ALL', '*', function(){
    RateLimiter::procesar();
});

Router::antes('ALL', '*', function(){
    CsrfMiddleware::procesar();
});

Router::antes('ALL', '*', function(){
    AuthMiddleware::procesar();
});

// ==================== MIDDLEWARE DE ADMIN ====================
Router::antes('ALL', 'a/*', function(){
    RoleMiddleware::procesar(['Admin', 'Superusuario']);
});

// ==================== MIDDLEWARE DE PROFESOR ====================
Router::antes('ALL', 'p/*', function(){
    RoleMiddleware::procesar(['Admin', 'Superusuario', 'Profesor']);
});

// ==================== MIDDLEWARE DE ESTUDIANTES ====================
Router::antes('ALL', 'u/*', function(){
    RoleMiddleware::procesar(['Admin', 'Superusuario', 'Estudiante']);
});

// ==================== MIDDLEWARE DE NOTIFICACIONES ====================
Router::antes('ALL', 'notifications/*', function(){
    AuthMiddleware::procesar();
});

// ==================== MIDDLEWARE DE PERFIL API ====================
Router::antes('ALL', 'perfil/data', function(){
    AuthMiddleware::procesar();
});
Router::antes('ALL', 'perfil/update', function(){
    AuthMiddleware::procesar();
});
Router::antes('ALL', 'perfil/password', function(){
    AuthMiddleware::procesar();
});
Router::antes('ALL', 'perfil/avatar', function(){
    AuthMiddleware::procesar();
});

// ==================== CARGAR RUTAS POR MÓDULOS ====================
foreach (glob(BASE_PATH . '/src/Modules/Rutas/*.php') as $rutaArchivo) {
    require_once $rutaArchivo;
}

// ==================== MANEJO DE ERRORES ====================
Router::rutaNoEncontrada(function () {
    http_response_code(404);
    cargar_controlador('Errors', 'errorController.php');
    error_404();
});

Router::metodoNoPermitido(function () {
    http_response_code(405);
    cargar_controlador('Errors', 'errorController.php');
    error_405();
});
