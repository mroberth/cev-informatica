<?php
use Core\Http\Router;

Router::get('a/usuarios/crear', function(){
	cargar_controlador('Usuarios', 'usuariosController.php');
	crear_usuarios();
});

Router::get('a/usuarios/consultar', function(){
	cargar_controlador('Usuarios', 'usuariosController.php');
	consultar_usuarios();
});

Router::get('a/usuarios/obtener_roles', function(){
	cargar_controlador('Usuarios','usuariosController.php');
	obtener_roles();
});

Router::get('a/usuarios/verificar_correo', function(){
	cargar_controlador('Usuarios','usuariosController.php');
	validar_correo();
});

Router::get('a/usuarios/verificar_cedula', function(){
	cargar_controlador('Usuarios','usuariosController.php');
	validar_cedula();
});

Router::get('a/usuarios/verificar_telefono', function(){
	cargar_controlador('Usuarios','usuariosController.php');
	validar_telefono();
});

Router::post('a/usuarios/registrar_usuarios', function(){
	cargar_controlador('Usuarios','usuariosController.php');
	registrar_usuarios();
});

Router::get('a/usuarios/consultar_usuarios', function(){
	cargar_controlador('Usuarios','usuariosController.php');
	consultar_usuarios_data();
});

Router::get('a/usuarios/obtener_usuario', function(){
	cargar_controlador('Usuarios','usuariosController.php');
	obtener_usuario();
});

Router::post('a/usuarios/actualizar_usuario', function(){
	cargar_controlador('Usuarios','usuariosController.php');
	actualizar_usuario();
});

Router::post('a/usuarios/cambiar_estado', function(){
	cargar_controlador('Usuarios','usuariosController.php');
	cambiar_estado();
});
