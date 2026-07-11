<?php
use Core\Http\Router;

Router::get('a/control-acceso', function() {
    cargar_controlador('ControlAcceso', 'controlAccesoController.php');
    control_acceso();
});

Router::get('a/control-acceso/obtener_matriz', function() {
    cargar_controlador('ControlAcceso', 'controlAccesoController.php');
    obtener_matriz_permisos();
});

Router::get('a/control-acceso/obtener_permisos_rol', function() {
    cargar_controlador('ControlAcceso', 'controlAccesoController.php');
    obtener_permisos_rol();
});

Router::post('a/control-acceso/guardar', function() {
    cargar_controlador('ControlAcceso', 'controlAccesoController.php');
    guardar_permisos_rol();
});
