<?php
use Core\Http\Router;

Router::get('login', function(){
    cargar_controlador('Auth', 'loginController.php');
    mostrarLogin();
});
