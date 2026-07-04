<?php
use Core\Http\Router;

Router::get('a/trayectos/consultar', function() {
    cargar_controlador('Trayectos', 'trayectosController.php');
    consultar_trayectos();
});

Router::get('a/trayectos/consultar_trayectos', function() {
    cargar_controlador('Trayectos', 'trayectosController.php');
    consultar_trayectos_data();
});
