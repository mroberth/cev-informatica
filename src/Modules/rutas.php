<?php

use Core\Http\Router;

// ==================== CARGAR RUTAS POR MÓDULOS ====================
foreach (glob(BASE_PATH . '/src/Modules/Rutas/*.php') as $rutaArchivo) {
    require_once $rutaArchivo;
}

// ==================== MANEJO DE ERRORES ====================
Router::rutaNoEncontrada(function () {
    http_response_code(404);
    cargar_controlador('Errors', 'errorController.php', 'error_404');
    error_404();
});

Router::metodoNoPermitido(function () {
    http_response_code(405);
    cargar_controlador('Errors', 'errorController.php', 'error_405');
    error_405();
});
