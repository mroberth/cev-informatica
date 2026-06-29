<?php
use Core\Http\Router;
use Core\Middleware\RoleMiddleware;

// Solo Admin puede acceder a /a/dashboard
Router::antes('ALL', 'a/dashboard', function(){
    RoleMiddleware::procesar('Admin');
});

Router::get('a/dashboard', function(){
    cargar_controlador('Dashboard', 'dashboardController.php');
    mostrarDashboard();
});

Router::get('u/dashboard', function(){
    cargar_controlador('Dashboard', 'dashboardController.php');
    mostrarDashboard();
});
