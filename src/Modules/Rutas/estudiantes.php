<?php
use Core\Http\Router;

Router::get('a/estudiantes/crear', function(){
    cargar_controlador('Estudiantes', 'estudiantesController.php');
    crear_estudiantes();
});

Router::get('a/estudiantes/consultar', function(){
    cargar_controlador('Estudiantes', 'estudiantesController.php');
    consultar_estudiantes();
});

Router::get('a/estudiantes/obtener', function(){
    cargar_controlador('Estudiantes', 'estudiantesController.php');
    obtener_estudiantes();
});

Router::get('a/estudiantes/obtener_registrados', function(){
    cargar_controlador('Estudiantes', 'estudiantesController.php');
    obtener_estudiantes_registrados();
});

Router::get('a/estudiantes/obtener_estudiante', function(){
    cargar_controlador('Estudiantes', 'estudiantesController.php');
    obtener_estudiante();
});

Router::post('a/estudiantes/registrar', function() {
    cargar_controlador('Estudiantes', 'estudiantesController.php');
    registrar_estudiante();
});

Router::post('a/estudiantes/actualizar', function() {
    cargar_controlador('Estudiantes', 'estudiantesController.php');
    actualizar_estudiante();
});
