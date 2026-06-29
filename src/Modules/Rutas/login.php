<?php
use Core\Http\Router;

Router::get('login', function(){
    cargar_controlador('Auth', 'loginController.php');
    mostrarLogin();
});

Router::post('iniciar_sesion', function(){
    cargar_controlador('Auth', 'loginController.php');
    iniciar_sesion();
});

Router::post('refresh', function(){
    cargar_controlador('Auth', 'loginController.php');
    refrescar_token();
});

Router::post('logout', function(){
    cargar_controlador('Auth', 'loginController.php');
    cerrar_sesion();
});
