<?php
use Core\Http\Router;

Router::get('a/bitacora', function(){
    cargar_controlador('Bitacora','bitacoraController.php');
    cargar_bitacora();
});

Router::get('a/bitacora/consultar_bitacora', function(){
    cargar_controlador('Bitacora','bitacoraController.php');
    obtener_bitacora();
});

Router::post('a/bitacora/registrar_bitacora', function(){
    cargar_controlador('Bitacora','bitacoraController.php');
    registrar_bitacora();
});
