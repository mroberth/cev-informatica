<?php
use Core\Http\Router;

Router::get('a/reportes', function () {
    cargar_controlador('Reportes', 'reportesController.php');
    reportes();
});

Router::get('a/reportes/data', function () {
    cargar_controlador('Reportes', 'reportesController.php');
    reportes_data();
});
