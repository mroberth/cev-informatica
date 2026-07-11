<?php

use App\ControlAcceso\Repository\ControlAccesoRepository;

function control_acceso(): void {
    require_once BASE_PATH . '/src/views/control_acceso/control_acceso.php';
}

function obtener_matriz_permisos(): void {
    header('Content-Type: application/json; charset=utf-8');
    try {
        $repositorio = new ControlAccesoRepository();
        echo json_encode([
            'data' => [
                'roles' => $repositorio->obtener_roles(),
                'modulos' => $repositorio->obtener_modulos(),
                'permisos' => $repositorio->obtener_permisos(),
            ]
        ], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function obtener_permisos_rol(): void {
    header('Content-Type: application/json; charset=utf-8');
    $idRol = (int) ($_GET['id_rol'] ?? 0);
    if ($idRol <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'ID de rol inválido.']);
        exit;
    }
    try {
        $repositorio = new ControlAccesoRepository();
        $permisos = $repositorio->obtener_permisos_por_rol($idRol);
        echo json_encode(['data' => $permisos], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function guardar_permisos_rol(): void {
    header('Content-Type: application/json; charset=utf-8');

    $input = json_decode(file_get_contents('php://input'), true);
    if (!is_array($input)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'error' => 'Datos inválidos.']);
        return;
    }

    $idRol = (int) ($input['id_rol'] ?? 0);
    $permisos = $input['permisos'] ?? [];

    if ($idRol <= 0) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'error' => 'El rol es obligatorio.']);
        return;
    }

    if (!is_array($permisos)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'error' => 'Formato de permisos inválido.']);
        return;
    }

    try {
        $repositorio = new ControlAccesoRepository();
        $repositorio->guardar_permisos($idRol, $permisos);

        registrar_en_bitacora(
            'ACTUALIZAR',
            "Permisos actualizados para rol ID: {$idRol}"
        );

        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'message' => 'Permisos guardados correctamente.'
        ], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}
