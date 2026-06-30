<?php

use Core\Http\Router;
use Core\Middleware\AuthMiddleware;
use Core\Middleware\RoleMiddleware;

// ==================== MIDDLEWARE GLOBAL ====================
Router::antes('ALL', '*', function(){
    AuthMiddleware::procesar();
});

// ==================== MIDDLEWARE DE ADMIN ====================
Router::antes('ALL', 'a/*', function(){
    RoleMiddleware::procesar('Admin');
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
