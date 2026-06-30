<?php
use Core\Http\Router;

Router::get('a/usuarios/crear', function(){
	cargar_controlador('Usuarios', 'usuariosController.php');
	crear_usuarios();
});
