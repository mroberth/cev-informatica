<?php

use Core\Http\Router;

Router::get('p/dashboard', function () {
    cargar_controlador('Profesor', 'profesorController.php');
    profesor_dashboard();
});

Router::get('p/dashboard/data', function () {
    cargar_controlador('Profesor', 'profesorController.php');
    profesor_dashboard_data();
});

Router::get('p/materias', function () {
    cargar_controlador('Profesor', 'profesorController.php');
    profesor_materias();
});

Router::get('p/materias/data', function () {
    cargar_controlador('Profesor', 'profesorController.php');
    profesor_materias_data();
});

Router::get('p/materias/{id}', function () {
    cargar_controlador('Profesor', 'profesorController.php');
    profesor_materia();
});
