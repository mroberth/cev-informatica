<?php
use Core\Http\Router;

Router::get('', function(){
    cargar_controlador('Landing', 'landingController.php');
    mostrarLanding();
});

Router::get('inicio', function(){
    cargar_controlador('Landing', 'landingController.php');
    mostrarLanding();
});