<?php
use Core\Http\Router;

// Vistas
Router::get('a/configuracion/crear', function() {
    cargar_controlador('Configuracion','configController.php');
    configuracion();
});

Router::get('a/configuracion/consultar', function() {
    cargar_controlador('Configuracion','configController.php');
    consultar_configuracion();
});

// ==================== VERIFICAR DUPLICADOS ====================
Router::get('a/configuracion/verificar_rol', function() {
    cargar_controlador('Configuracion','configController.php');
    verificar_rol();
});

Router::get('a/configuracion/verificar_modulo', function() {
    cargar_controlador('Configuracion','configController.php');
    verificar_modulo();
});

Router::get('a/configuracion/verificar_permiso', function() {
    cargar_controlador('Configuracion','configController.php');
    verificar_permiso();
});

// ==================== ROLES ====================
Router::get('a/configuracion/obtener_roles', function() {
    cargar_controlador('Configuracion','configController.php');
    obtener_roles_config();
});

Router::post('a/configuracion/guardar_rol', function() {
    cargar_controlador('Configuracion','configController.php');
    guardar_rol();
});

Router::post('a/configuracion/actualizar_rol', function() {
    cargar_controlador('Configuracion','configController.php');
    actualizar_rol();
});

// ==================== MODULOS ====================
Router::get('a/configuracion/obtener_modulos', function() {
    cargar_controlador('Configuracion','configController.php');
    obtener_modulos_config();
});

Router::post('a/configuracion/guardar_modulo', function() {
    cargar_controlador('Configuracion','configController.php');
    guardar_modulo();
});

Router::post('a/configuracion/actualizar_modulo', function() {
    cargar_controlador('Configuracion','configController.php');
    actualizar_modulo();
});

// ==================== PERMISOS ====================
Router::get('a/configuracion/obtener_permisos', function() {
    cargar_controlador('Configuracion','configController.php');
    obtener_permisos_config();
});

Router::post('a/configuracion/guardar_permiso', function() {
    cargar_controlador('Configuracion','configController.php');
    guardar_permiso();
});

Router::post('a/configuracion/actualizar_permiso', function() {
    cargar_controlador('Configuracion','configController.php');
    actualizar_permiso();
});
