<?php

use Core\Http\Router;

Router::get('notifications/stream', function () {
    cargar_controlador('Notificacion', 'notificacionController.php');
    notification_stream();
});

Router::get('notifications', function () {
    cargar_controlador('Notificacion', 'notificacionController.php');
    notification_listar();
});

Router::post('notifications/{id}/read', function () {
    cargar_controlador('Notificacion', 'notificacionController.php');
    notification_marcar_leida();
});

Router::post('notifications/read-all', function () {
    cargar_controlador('Notificacion', 'notificacionController.php');
    notification_marcar_todas_leidas();
});
