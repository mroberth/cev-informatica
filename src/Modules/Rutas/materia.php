<?php

use Core\Http\Router;

// ==================== RUTAS ESTUDIANTE ====================

Router::get('u/materia/{id}', function () {
    cargar_controlador('Materia', 'materiaController.php');
    student_materia();
});

Router::get('u/materia/{id}/recursos', function () {
    cargar_controlador('Materia', 'materiaController.php');
    student_materia_recursos();
});

Router::get('u/materia/{id}/evaluaciones', function () {
    cargar_controlador('Materia', 'materiaController.php');
    student_materia_evaluaciones();
});

// ==================== RUTAS PROFESOR / ADMIN ====================

Router::get('p/materias/{id}/recursos', function () {
    cargar_controlador('Materia', 'materiaController.php');
    profesor_materia_recursos_listar();
});

Router::post('p/materias/{id}/recursos', function () {
    cargar_controlador('Materia', 'materiaController.php');
    profesor_materia_recurso_crear();
});

Router::delete('p/materias/recursos/{id}', function () {
    cargar_controlador('Materia', 'materiaController.php');
    profesor_materia_recurso_eliminar();
});

// ==================== RUTAS EVALUACIONES (PROFESOR) ====================

Router::get('p/materias/{id}/evaluaciones', function () {
    cargar_controlador('Materia', 'materiaController.php');
    profesor_evaluaciones_listar();
});

Router::post('p/materias/{id}/evaluaciones', function () {
    cargar_controlador('Materia', 'materiaController.php');
    profesor_evaluacion_crear();
});

Router::delete('p/materias/evaluaciones/{id}', function () {
    cargar_controlador('Materia', 'materiaController.php');
    profesor_evaluacion_eliminar();
});

Router::get('p/materias/evaluaciones/{id}/calificaciones', function () {
    cargar_controlador('Materia', 'materiaController.php');
    profesor_calificaciones_listar();
});

Router::post('p/materias/evaluaciones/{id}/calificaciones', function () {
    cargar_controlador('Materia', 'materiaController.php');
    profesor_calificaciones_guardar();
});

// ==================== RUTAS NOTAS (ESTUDIANTE) ====================

Router::get('u/materia/{id}/notas', function () {
    cargar_controlador('Materia', 'materiaController.php');
    student_materia_notas();
});

// ==================== RUTAS CALENDARIO (ESTUDIANTE) ====================

Router::get('u/calendar/events', function () {
    cargar_controlador('Materia', 'materiaController.php');
    student_calendar_events();
});

// ==================== RUTAS ENTREGAS (ESTUDIANTE) ====================

Router::post('u/evaluaciones/{id}/entregar', function () {
    cargar_controlador('Materia', 'materiaController.php');
    student_evaluacion_entregar();
});

// ==================== RUTAS ENTREGAS (PROFESOR) ====================

Router::get('p/materias/evaluaciones/{id}/entregas', function () {
    cargar_controlador('Materia', 'materiaController.php');
    profesor_evaluacion_entregas();
});
