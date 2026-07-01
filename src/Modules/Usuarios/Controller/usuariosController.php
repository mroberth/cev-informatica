<?php

use App\Usuarios\DTO\UsuarioDTO;
use App\Usuarios\Repository\UsuariosRepository;
use App\Usuarios\Service\UsuarioService;

function crear_usuarios(): void {
    require_once BASE_PATH . '/src/views/usuarios/crear_usuarios.php';
}

function consultar_usuarios(): void {
    require_once BASE_PATH . '/src/views/usuarios/consultar_usuarios.php';
}

function obtener_roles(): void {
    header('Content-Type: application/json; charset=utf-8');

    $repositorio = new UsuariosRepository();
    $roles = $repositorio->obtener_roles();

    echo json_encode(['data' => $roles]);
    exit;
}

function validar_correo(): void {
    header('Content-Type: application/json; charset=utf-8');

    $correo = $_GET['correo'] ?? '';

    if (empty($correo)) {
        http_response_code(400);
        echo json_encode(['error' => 'El correo es obligatorio.']);
        exit;
    }

    $repositorio = new UsuariosRepository();
    $existe = $repositorio->validar_correo($correo);

    echo json_encode(['existe' => $existe]);
    exit;
}

function registrar_usuarios() : void {
    header('Content-Type: application/json; charset=utf-8');

    $input = json_decode(file_get_contents('php://input'), true);

    if (!is_array($input)) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'error' => 'Datos inválidos o incompletos.'
        ], JSON_UNESCAPED_UNICODE);
        return;
    }

    $usuarioDTO = new UsuarioDTO(
        0,
        (string) ($input['nombre'] ?? ''),
        (string) ($input['apellido'] ?? ''),
        (string) ($input['correo'] ?? ''),
        (string) ($input['password'] ?? ''),
        (int) ($input['rol_id'] ?? 0),
        (string) ($input['estado'] ?? '')
    );

    try {
        $service = new UsuarioService();
        $repositorio = new UsuariosRepository();

        $usuarioValidado = $service->validarUsuario($usuarioDTO);
        $idInsertado = $repositorio->registrar_usuario($usuarioValidado);

        http_response_code(201);
        echo json_encode([
            'status' => 'success',
            'data' => [
                'id' => $idInsertado,
                'message' => 'Usuario registrado correctamente.'
            ]
        ], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;

        http_response_code($code);
        echo json_encode([
            'status' => 'error',
            'error' => $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
    }
}

function consultar_usuarios_data(): void {
    header('Content-Type: application/json; charset=utf-8');

    $repositorio = new UsuariosRepository();
    $usuarios = $repositorio->consultar_usuarios();

    echo json_encode(['data' => $usuarios]);
    exit;
}