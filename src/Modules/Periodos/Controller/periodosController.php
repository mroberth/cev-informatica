<?php

use App\Periodos\DTO\PeriodoDTO;
use App\Periodos\Repository\PeriodoRepository;
use App\Periodos\Service\PeriodoService;

function crear_periodos(): void {
    require_once BASE_PATH . '/src/views/periodos/crear_periodos.php';
}

function consultar_periodos(): void {
    require_once BASE_PATH . '/src/views/periodos/consultar_periodos.php';
}

function registrar_periodo(): void {
    header('Content-Type: application/json; charset=utf-8');

    $input = json_decode(file_get_contents('php://input'), true);
    if (!is_array($input)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'error' => 'Datos inválidos.']);
        return;
    }

    $periodoDTO = new PeriodoDTO(
        0,
        trim((string) ($input['nombre'] ?? '')),
        (string) ($input['fecha_inicio'] ?? ''),
        (string) ($input['fecha_fin'] ?? ''),
        (string) ($input['estado'] ?? '')
    );

    try {
        $service = new PeriodoService();
        $repositorio = new PeriodoRepository();
        $periodoValidado = $service->validar($periodoDTO);

        if ($repositorio->validar_nombre($periodoValidado->getNombre())) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'error' => 'El nombre del período ya existe.']);
            return;
        }

        $idInsertado = $repositorio->registrar($periodoValidado);

        registrar_en_bitacora(
            'CREAR',
            "Período académico creado: {$periodoValidado->getNombre()} (ID: {$idInsertado})"
        );

        http_response_code(201);
        echo json_encode([
            'status' => 'success',
            'data' => [
                'id' => $idInsertado,
                'message' => 'Período registrado correctamente.'
            ]
        ], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode(['status' => 'error', 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function consultar_periodos_data(): void {
    header('Content-Type: application/json; charset=utf-8');

    try {
        $repositorio = new PeriodoRepository();
        $periodos = $repositorio->consultar();
        echo json_encode(['data' => $periodos], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function obtener_periodo(): void {
    header('Content-Type: application/json; charset=utf-8');

    $id = (int) ($_GET['id'] ?? 0);
    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'ID inválido.']);
        exit;
    }

    try {
        $repositorio = new PeriodoRepository();
        $periodo = $repositorio->obtener_por_id($id);
        if (!$periodo) {
            http_response_code(404);
            echo json_encode(['error' => 'Período no encontrado.']);
            exit;
        }
        echo json_encode(['data' => $periodo], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function actualizar_periodo(): void {
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

    $periodoDTO = new PeriodoDTO(
        $id,
        trim((string) ($input['nombre'] ?? '')),
        (string) ($input['fecha_inicio'] ?? ''),
        (string) ($input['fecha_fin'] ?? ''),
        (string) ($input['estado'] ?? '')
    );

    try {
        $service = new PeriodoService();
        $repositorio = new PeriodoRepository();
        $periodoValidado = $service->validar($periodoDTO);

        if ($repositorio->validar_nombre($periodoValidado->getNombre(), $id)) {
            http_response_code(400);
            echo json_encode(['error' => 'El nombre del período ya existe en otro registro.']);
            return;
        }

        $repositorio->actualizar($periodoValidado);

        registrar_en_bitacora(
            'ACTUALIZAR',
            "Período académico actualizado: {$periodoValidado->getNombre()} (ID: {$id})"
        );

        echo json_encode(['status' => 'success', 'message' => 'Período actualizado correctamente.'], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function verificar_nombre_periodo(): void {
    header('Content-Type: application/json; charset=utf-8');

    $nombre = $_GET['nombre'] ?? '';
    $idExcluir = isset($_GET['id_excluir']) ? (int) $_GET['id_excluir'] : null;

    if (empty($nombre)) {
        http_response_code(400);
        echo json_encode(['error' => 'El nombre del período es obligatorio.']);
        exit;
    }

    try {
        $repositorio = new PeriodoRepository();
        $existe = $repositorio->validar_nombre($nombre, $idExcluir);
        echo json_encode(['existe' => $existe]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}
