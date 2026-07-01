<?php
use Core\Http\Router;

Router::get('a/usuarios/crear', function(){
	cargar_controlador('Usuarios', 'usuariosController.php');
	crear_usuarios();
});

Router::get('a/usuarios/obtener_roles', function(){
	cargar_controlador('Usuarios','usuariosController.php');
	obtener_roles();
});

Router::get('a/usuarios/verificar_correo', function(){
	cargar_controlador('Usuarios','usuariosController.php');
	validar_correo();
});

Router::post('a/usuarios/registrar_usuarios', function(){
	cargar_controlador('Usuarios','usuariosController.php');
	registrar_usuarios();
});
