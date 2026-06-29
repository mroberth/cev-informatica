<?php
use Core\Http\Router;
use Core\Middleware\GuestMiddleware;

// Si ya tiene sesión activa, que no vea el login
Router::antes('GET', 'login', function(){
    GuestMiddleware::procesar();
});

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
