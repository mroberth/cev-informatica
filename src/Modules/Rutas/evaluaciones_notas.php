<?php
use Core\Http\Router;

Router::get('a/evaluaciones-notas', function () {
    cargar_controlador('EvaluacionesNotas', 'evaluacionesNotasController.php');
    evaluaciones_notas();
});

Router::get('a/evaluaciones-notas/data', function () {
    cargar_controlador('EvaluacionesNotas', 'evaluacionesNotasController.php');
    evaluaciones_notas_data();
});

Router::get('a/evaluaciones-notas/{id}/estudiantes', function () {
    cargar_controlador('EvaluacionesNotas', 'evaluacionesNotasController.php');
    evaluacion_estudiantes();
});
