<?php
use Core\Http\Router;

Router::get('a/dashboard', function(){
    cargar_controlador('Dashboard', 'dashboardController.php');
    mostrarDashboard();
});

Router::get('u/dashboard', function(){
    cargar_controlador('Dashboard', 'dashboardController.php');
    mostrarDashboard();
});
