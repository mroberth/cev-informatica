<?php


function mostrarLogin(): void{
    require_once BASE_PATH . '/src/views/login/login.php';
}

function iniciar_sesion(): void{
    $input = json_decode(file_get_contents(BASE_PATH .''), true);
    $correo = filter_var($input['correo'], FILTER_SANITIZE_STRING);
    $password = $input['password'];

    if(!$correo || !$password){
        throw new Exception('Credenciales inválidas o incompletas.');
    }
}