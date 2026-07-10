<?php
use Core\Http\Router;

Router::get('a/asignacion-docente/crear', function() {
    cargar_controlador('AsignacionDocente', 'asignacionDocenteController.php');
    crear_asignacion_docente();
});

Router::get('a/asignacion-docente/consultar', function() {
    cargar_controlador('AsignacionDocente', 'asignacionDocenteController.php');
    consultar_asignacion_docente();
});

Router::get('a/asignacion-docente/obtener_periodos', function() {
    cargar_controlador('AsignacionDocente', 'asignacionDocenteController.php');
    obtener_periodos_asignacion();
});

Router::get('a/asignacion-docente/obtener_secciones', function() {
    cargar_controlador('AsignacionDocente', 'asignacionDocenteController.php');
    obtener_secciones_por_periodo_asignacion();
});

Router::get('a/asignacion-docente/obtener_docentes', function() {
    cargar_controlador('AsignacionDocente', 'asignacionDocenteController.php');
    obtener_docentes_asignacion();
});

Router::get('a/asignacion-docente/obtener_datos_seccion', function() {
    cargar_controlador('AsignacionDocente', 'asignacionDocenteController.php');
    obtener_datos_seccion_asignacion();
});

Router::post('a/asignacion-docente/guardar', function() {
    cargar_controlador('AsignacionDocente', 'asignacionDocenteController.php');
    guardar_asignaciones();
});

Router::get('a/asignacion-docente/consultar_data', function() {
    cargar_controlador('AsignacionDocente', 'asignacionDocenteController.php');
    consultar_asignaciones_data();
});

Router::post('a/asignacion-docente/eliminar', function() {
    cargar_controlador('AsignacionDocente', 'asignacionDocenteController.php');
    eliminar_asignacion();
});
