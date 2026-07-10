<?php
use Core\Http\Router;

Router::get('a/inscripciones/crear', function() {
    cargar_controlador('Inscripciones', 'inscripcionesController.php');
    crear_inscripciones();
});

Router::get('a/inscripciones/consultar', function() {
    cargar_controlador('Inscripciones', 'inscripcionesController.php');
    consultar_inscripciones();
});

Router::get('a/inscripciones/obtener_periodos', function() {
    cargar_controlador('Inscripciones', 'inscripcionesController.php');
    obtener_periodos_inscripcion();
});

Router::get('a/inscripciones/obtener_secciones', function() {
    cargar_controlador('Inscripciones', 'inscripcionesController.php');
    obtener_secciones_inscripcion();
});

Router::get('a/inscripciones/obtener_datos_seccion', function() {
    cargar_controlador('Inscripciones', 'inscripcionesController.php');
    obtener_datos_seccion_inscripcion();
});

Router::post('a/inscripciones/guardar', function() {
    cargar_controlador('Inscripciones', 'inscripcionesController.php');
    guardar_inscripciones();
});

Router::get('a/inscripciones/consultar_data', function() {
    cargar_controlador('Inscripciones', 'inscripcionesController.php');
    consultar_inscripciones_data();
});

Router::post('a/inscripciones/eliminar', function() {
    cargar_controlador('Inscripciones', 'inscripcionesController.php');
    eliminar_inscripcion();
});
