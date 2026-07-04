<?php
use Core\Http\Router;

Router::get('a/periodos/crear', function() {
    cargar_controlador('Periodos', 'periodosController.php');
    crear_periodos();
});

Router::get('a/periodos/consultar', function() {
    cargar_controlador('Periodos', 'periodosController.php');
    consultar_periodos();
});

Router::get('a/periodos/consultar_periodos', function() {
    cargar_controlador('Periodos', 'periodosController.php');
    consultar_periodos_data();
});

Router::post('a/periodos/registrar', function() {
    cargar_controlador('Periodos', 'periodosController.php');
    registrar_periodo();
});

Router::get('a/periodos/obtener_periodo', function() {
    cargar_controlador('Periodos', 'periodosController.php');
    obtener_periodo();
});

Router::post('a/periodos/actualizar', function() {
    cargar_controlador('Periodos', 'periodosController.php');
    actualizar_periodo();
});

Router::get('a/periodos/verificar_nombre', function() {
    cargar_controlador('Periodos', 'periodosController.php');
    verificar_nombre_periodo();
});
