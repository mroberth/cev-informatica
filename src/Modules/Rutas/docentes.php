<?php
use Core\Http\Router;

Router::get('a/docentes/crear', function(){
    cargar_controlador('Docentes', 'docentesController.php');
    crear_docentes();
});

Router::get('a/docentes/consultar', function(){
    cargar_controlador('Docentes', 'docentesController.php');
    consultar_docentes();
});

Router::get('a/docentes/obtener', function(){
    cargar_controlador('Docentes', 'docentesController.php');
    obtener_docentes();
});

Router::get('a/docentes/obtener_registrados', function(){
    cargar_controlador('Docentes', 'docentesController.php');
    obtener_docentes_registrados();
});

Router::get('a/docentes/obtener_docente', function(){
    cargar_controlador('Docentes', 'docentesController.php');
    obtener_docente();
});

Router::post('a/docentes/registrar', function() {
    cargar_controlador('Docentes', 'docentesController.php');
    registrar_docente();
});

Router::post('a/docentes/actualizar', function() {
    cargar_controlador('Docentes', 'docentesController.php');
    actualizar_docente();
});
