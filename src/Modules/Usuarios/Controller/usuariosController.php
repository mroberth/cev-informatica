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

function obtener_usuario(): void {
    header('Content-Type: application/json; charset=utf-8');

    $id = $_GET['id'] ?? 0;

    if (empty($id) || !is_numeric($id)) {
        http_response_code(400);
        echo json_encode(['error' => 'ID de usuario inválido.']);
        exit;
    }

    $repositorio = new UsuariosRepository();
    $usuario = $repositorio->obtener_usuario_por_id((int) $id);

    if (!$usuario) {
        http_response_code(404);
        echo json_encode(['error' => 'Usuario no encontrado.']);
        exit;
    }

    echo json_encode(['data' => $usuario]);
    exit;
}

function actualizar_usuario(): void {
    header('Content-Type: application/json; charset=utf-8');

    $input = json_decode(file_get_contents('php://input'), true);

    if (!is_array($input)) {
        http_response_code(400);
        echo json_encode(['error' => 'Datos inválidos.']);
        return;
    }

    $id = (int) ($input['id'] ?? 0);
    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'ID de usuario inválido.']);
        return;
    }

    $usuarioDTO = new UsuarioDTO(
        $id,
        (string) ($input['nombre'] ?? ''),
        (string) ($input['apellido'] ?? ''),
        (string) ($input['correo'] ?? ''),
        '',
        (int) ($input['rol_id'] ?? 0),
        (string) ($input['estado'] ?? '')
    );

    $password = $input['password'] ?? '';

    try {
        $service = new UsuarioService();
        $usuarioValidado = $service->validarUsuarioSinPassword($usuarioDTO);

        $repositorio = new UsuariosRepository();
        $actualizado = $repositorio->actualizar_usuario($usuarioValidado, $password);

        if ($actualizado) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Usuario actualizado correctamente.'
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'No se pudo actualizar el usuario.']);
        }
    } catch (Exception $e) {
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}

function cambiar_estado(): void {
    header('Content-Type: application/json; charset=utf-8');

    $input = json_decode(file_get_contents('php://input'), true);

    if (!is_array($input)) {
        http_response_code(400);
        echo json_encode(['error' => 'Datos inválidos.']);
        return;
    }

    $id = (int) ($input['id'] ?? 0);
    $estado = (string) ($input['estado'] ?? '');

    if ($id <= 0 || !in_array($estado, ['activo', 'inactivo'], true)) {
        http_response_code(400);
        echo json_encode(['error' => 'Parámetros inválidos.']);
        return;
    }

    try {
        $repositorio = new UsuariosRepository();
        $usuario = $repositorio->obtener_usuario_por_id($id);

        if (!$usuario) {
            http_response_code(404);
            echo json_encode(['error' => 'Usuario no encontrado.']);
            return;
        }

        if ($estado === 'inactivo' && $usuario['rol'] === 'Admin') {
            $adminsActivos = $repositorio->contar_administradores_activos();
            if ($adminsActivos <= 1) {
                http_response_code(400);
                echo json_encode(['error' => 'No puedes desactivar al único administrador activo del sistema.']);
                return;
            }
        }

        $resultado = $repositorio->cambiar_estado($id, $estado);

        if ($resultado) {
            $accion = $estado === 'activo' ? 'activado' : 'desactivado';
            echo json_encode([
                'status' => 'success',
                'message' => "Usuario {$accion} correctamente."
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'No se pudo cambiar el estado del usuario.']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}