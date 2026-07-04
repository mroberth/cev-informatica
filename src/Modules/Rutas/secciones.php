<?php
use Core\Http\Router;

Router::get('a/secciones/crear', function() {
    cargar_controlador('Secciones', 'seccionesController.php');
    crear_secciones();
});

Router::get('a/secciones/consultar', function() {
    cargar_controlador('Secciones', 'seccionesController.php');
    consultar_secciones();
});

Router::get('a/secciones/consultar_secciones', function() {
    cargar_controlador('Secciones', 'seccionesController.php');
    consultar_secciones_data();
});

Router::post('a/secciones/registrar', function() {
    cargar_controlador('Secciones', 'seccionesController.php');
    registrar_seccion();
});

Router::get('a/secciones/obtener_seccion', function() {
    cargar_controlador('Secciones', 'seccionesController.php');
    obtener_seccion();
});

Router::post('a/secciones/actualizar', function() {
    cargar_controlador('Secciones', 'seccionesController.php');
    actualizar_seccion();
});

Router::get('a/secciones/verificar_codigo', function() {
    cargar_controlador('Secciones', 'seccionesController.php');
    verificar_codigo_seccion();
});

Router::get('a/secciones/obtener_periodos', function() {
    cargar_controlador('Secciones', 'seccionesController.php');
    obtener_periodos_secciones();
});

Router::get('a/secciones/obtener_trayectos', function() {
    cargar_controlador('Secciones', 'seccionesController.php');
    obtener_trayectos_secciones();
});

Router::get('a/secciones/obtener_secciones', function() {
    cargar_controlador('Secciones', 'seccionesController.php');
    obtener_secciones();
});
