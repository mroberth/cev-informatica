<?php

use App\Estudiantes\DTO\EstudianteDTO;
use App\Estudiantes\Repository\EstudiantesRepository;
use App\Estudiantes\Service\EstudiantesService;

function crear_estudiantes(): void {
    require_once BASE_PATH . '/src/views/estudiantes/crear_estudiantes.php';
}

function consultar_estudiantes(): void {
    require_once BASE_PATH . '/src/views/estudiantes/consultar_estudiantes.php';
}

function obtener_estudiantes(): void {
    header('Content-Type: application/json; charset=utf-8');

    try {
        $repository = new EstudiantesRepository();
        $estudiantes = $repository->consultar_estudiantes();
        echo json_encode(['data' => $estudiantes], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }

    exit;
}

function registrar_estudiante(): void {
    header('Content-Type: application/json; charset=utf-8');

    $input = json_decode(file_get_contents('php://input'), true);
    if (!is_array($input)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'error' => 'Datos inválidos.']);
        return;
    }

    $estudianteDTO = new EstudianteDTO(
        0,
        (int) ($input['id_usuario'] ?? 0),
        'Activo'
    );

    try {
        $service = new EstudiantesService();
        $repositorio = new EstudiantesRepository();
        $estudianteValidado = $service->validar($estudianteDTO);

        $idInsertado = $repositorio->registrar($estudianteValidado);

        registrar_en_bitacora(
            'CREAR',
            "Estudiante registrado (ID Usuario: {$estudianteValidado->getIdUsuario()}, ID Estudiante: {$idInsertado})"
        );

        http_response_code(201);
        echo json_encode([
            'status' => 'success',
            'data' => [
                'id' => $idInsertado,
                'message' => 'Estudiante registrado correctamente.'
            ]
        ], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode(['status' => 'error', 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function obtener_estudiantes_registrados(): void {
    header('Content-Type: application/json; charset=utf-8');

    try {
        $repository = new EstudiantesRepository();
        $estudiantes = $repository->obtener_estudiantes_registrados();
        echo json_encode(['data' => $estudiantes], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }

    exit;
}

function obtener_estudiante(): void {
    header('Content-Type: application/json; charset=utf-8');

    $id = (int) ($_GET['id'] ?? 0);
    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'ID inválido.']);
        exit;
    }

    try {
        $repositorio = new EstudiantesRepository();
        $estudiante = $repositorio->obtener_por_id($id);
        if (!$estudiante) {
            http_response_code(404);
            echo json_encode(['error' => 'Estudiante no encontrado.']);
            exit;
        }
        echo json_encode(['data' => $estudiante], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function actualizar_estudiante(): void {
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
        echo json_encode(['error' => 'ID inválido.']);
        return;
    }

    $estudianteDTO = new EstudianteDTO(
        $id,
        0,
        (string) ($input['estado_academico'] ?? '')
    );

    try {
        $service = new EstudiantesService();
        $repositorio = new EstudiantesRepository();
        $estudianteValidado = $service->validarActualizar($estudianteDTO);

        $repositorio->actualizar($estudianteValidado);

        registrar_en_bitacora(
            'ACTUALIZAR',
            "Estudiante actualizado (ID: {$id})"
        );

        echo json_encode(['status' => 'success', 'message' => 'Estudiante actualizado correctamente.'], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}
