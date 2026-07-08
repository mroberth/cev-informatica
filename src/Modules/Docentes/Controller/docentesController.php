<?php

use App\Docentes\DTO\DocenteDTO;
use App\Docentes\Repository\DocentesRepository;
use App\Docentes\Service\DocentesService;

function crear_docentes(): void {
    require_once BASE_PATH . '/src/views/docentes/crear_docentes.php';
}

function consultar_docentes(): void {
    require_once BASE_PATH . '/src/views/docentes/consultar_docentes.php';
}

function obtener_docentes(): void {
    header('Content-Type: application/json; charset=utf-8');

    try {
        $repository = new DocentesRepository();
        $docentes = $repository->consultar_docentes();
        echo json_encode(['data' => $docentes], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }

    exit;
}

function registrar_docente(): void {
    header('Content-Type: application/json; charset=utf-8');

    $input = json_decode(file_get_contents('php://input'), true);
    if (!is_array($input)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'error' => 'Datos inválidos.']);
        return;
    }

    $docenteDTO = new DocenteDTO(
        0,
        (int) ($input['id_usuario'] ?? 0),
        trim((string) ($input['especialidad'] ?? '')),
        'Activo'
    );

    try {
        $service = new DocentesService();
        $repositorio = new DocentesRepository();
        $docenteValidado = $service->validar($docenteDTO);

        $idInsertado = $repositorio->registrar($docenteValidado);

        registrar_en_bitacora(
            'CREAR',
            "Docente registrado (ID Usuario: {$docenteValidado->getIdUsuario()}, ID Docente: {$idInsertado})"
        );

        http_response_code(201);
        echo json_encode([
            'status' => 'success',
            'data' => [
                'id' => $idInsertado,
                'message' => 'Docente registrado correctamente.'
            ]
        ], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode(['status' => 'error', 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function obtener_docentes_registrados(): void {
    header('Content-Type: application/json; charset=utf-8');

    try {
        $repository = new DocentesRepository();
        $docentes = $repository->obtener_docentes_registrados();
        echo json_encode(['data' => $docentes], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }

    exit;
}

function obtener_docente(): void {
    header('Content-Type: application/json; charset=utf-8');

    $id = (int) ($_GET['id'] ?? 0);
    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'ID inválido.']);
        exit;
    }

    try {
        $repositorio = new DocentesRepository();
        $docente = $repositorio->obtener_por_id($id);
        if (!$docente) {
            http_response_code(404);
            echo json_encode(['error' => 'Docente no encontrado.']);
            exit;
        }
        echo json_encode(['data' => $docente], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function actualizar_docente(): void {
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

    $docenteDTO = new DocenteDTO(
        $id,
        0,
        trim((string) ($input['especialidad'] ?? '')),
        (string) ($input['estado'] ?? '')
    );

    try {
        $service = new DocentesService();
        $repositorio = new DocentesRepository();
        $docenteValidado = $service->validarActualizar($docenteDTO);

        $repositorio->actualizar($docenteValidado);

        registrar_en_bitacora(
            'ACTUALIZAR',
            "Docente actualizado (ID: {$id})"
        );

        echo json_encode(['status' => 'success', 'message' => 'Docente actualizado correctamente.'], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}
