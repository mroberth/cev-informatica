<?php

use Core\Http\Router;

Router::get('u/dashboard', function () {
    cargar_controlador('Student', 'studentController.php');
    student_dashboard();
});

Router::get('u/area-personal', function () {
    cargar_controlador('Student', 'studentController.php');
    student_area_personal();
});

Router::get('u/mis-cursos', function () {
    cargar_controlador('Student', 'studentController.php');
    student_mis_cursos();
});

Router::get('u/mis-cursos/data', function () {
    cargar_controlador('Student', 'studentController.php');
    student_mis_cursos_data();
});
