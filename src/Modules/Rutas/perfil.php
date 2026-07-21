<?php

use Core\Http\Router;

Router::get('a/perfil', function () {
    cargar_controlador('Profile', 'profileController.php');
    profile_show();
});

Router::get('p/perfil', function () {
    cargar_controlador('Profile', 'profileController.php');
    profile_show();
});

Router::get('u/perfil', function () {
    cargar_controlador('Profile', 'profileController.php');
    profile_show();
});

Router::get('perfil/data', function () {
    cargar_controlador('Profile', 'profileController.php');
    profile_data();
});

Router::post('perfil/update', function () {
    cargar_controlador('Profile', 'profileController.php');
    profile_update();
});

Router::post('perfil/password', function () {
    cargar_controlador('Profile', 'profileController.php');
    profile_password();
});

Router::post('perfil/avatar', function () {
    cargar_controlador('Profile', 'profileController.php');
    profile_avatar();
});
