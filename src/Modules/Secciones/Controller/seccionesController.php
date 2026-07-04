<?php

use App\Secciones\DTO\SeccionDTO;
use App\Secciones\Repository\SeccionRepository;
use App\Secciones\Service\SeccionService;

/**
 * Muestra la vista del formulario de creación de secciones
 */
function crear_secciones(): void {
    require_once BASE_PATH . '/src/views/secciones/crear_secciones.php';
}

/**
 * Muestra la vista del listado general de secciones
 */
function consultar_secciones(): void {
    require_once BASE_PATH . '/src/views/secciones/consultar_secciones.php';
}

/**
 * Registra una nueva sección en la base de datos
 */
function registrar_seccion(): void {
    header('Content-Type: application/json; charset=utf-8');

    $input = json_decode(file_get_contents('php://input'), true);
    if (!is_array($input)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'error' => 'Datos inválidos.']);
        return;
    }

    $seccionDTO = new SeccionDTO(
        0,
        (int) ($input['id_periodo'] ?? 0),
        (int) ($input['id_trayecto'] ?? 0),
        strtoupper(trim((string) ($input['codigo_seccion'] ?? ''))),
        (string) ($input['turno'] ?? '')
    );

    try {
        $service = new SeccionService();
        $repositorio = new SeccionRepository();
        $seccionValidada = $service->validar($seccionDTO);

        if ($repositorio->validar_codigo_seccion($seccionValidada->getCodigoSeccion())) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'error' => 'El código de sección ingresado ya existe.']);
            return;
        }

        $idInsertado = $repositorio->registrar($seccionValidada);

        registrar_en_bitacora(
            'CREAR',
            "Sección creada: {$seccionValidada->getCodigoSeccion()} (ID: {$idInsertado})"
        );

        http_response_code(201);
        echo json_encode([
            'status' => 'success',
            'data' => [
                'id' => $idInsertado,
                'message' => 'Sección registrada correctamente.'
            ]
        ], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode(['status' => 'error', 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

/**
 * Devuelve listado de secciones en formato JSON para DataTable
 */
function consultar_secciones_data(): void {
    header('Content-Type: application/json; charset=utf-8');

    try {
        $repositorio = new SeccionRepository();
        $secciones = $repositorio->consultar();
        echo json_encode(['data' => $secciones], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

/**
 * Devuelve una sección por ID
 */
function obtener_seccion(): void {
    header('Content-Type: application/json; charset=utf-8');

    $id = (int) ($_GET['id'] ?? 0);
    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'ID inválido.']);
        exit;
    }

    try {
        $repositorio = new SeccionRepository();
        $seccion = $repositorio->obtener_por_id($id);
        if (!$seccion) {
            http_response_code(404);
            echo json_encode(['error' => 'Sección no encontrada.']);
            exit;
        }
        echo json_encode(['data' => $seccion], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

/**
 * Actualiza una sección existente
 */
function actualizar_seccion(): void {
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

    $seccionDTO = new SeccionDTO(
        $id,
        (int) ($input['id_periodo'] ?? 0),
        (int) ($input['id_trayecto'] ?? 0),
        strtoupper(trim((string) ($input['codigo_seccion'] ?? ''))),
        (string) ($input['turno'] ?? '')
    );

    try {
        $service = new SeccionService();
        $repositorio = new SeccionRepository();
        $seccionValidada = $service->validar($seccionDTO);

        if ($repositorio->validar_codigo_seccion($seccionValidada->getCodigoSeccion(), $id)) {
            http_response_code(400);
            echo json_encode(['error' => 'El código de sección ya existe en otra sección.']);
            return;
        }

        $repositorio->actualizar($seccionValidada);

        registrar_en_bitacora(
            'ACTUALIZAR',
            "Sección actualizada: {$seccionValidada->getCodigoSeccion()} (ID: {$id})"
        );

        echo json_encode(['status' => 'success', 'message' => 'Sección actualizada correctamente.'], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

/**
 * Devuelve todos los períodos académicos en formato JSON
 */
function obtener_periodos_secciones(): void {
    header('Content-Type: application/json; charset=utf-8');

    try {
        $repositorio = new SeccionRepository();
        $periodos = $repositorio->obtener_periodos();
        echo json_encode(['data' => $periodos], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

/**
 * Devuelve todos los trayectos en formato JSON
 */
function verificar_codigo_seccion(): void {
    header('Content-Type: application/json; charset=utf-8');

    $codigo = $_GET['codigo_seccion'] ?? '';
    $idExcluir = isset($_GET['id_excluir']) ? (int) $_GET['id_excluir'] : null;

    if (empty($codigo)) {
        http_response_code(400);
        echo json_encode(['error' => 'El código de sección es obligatorio.']);
        exit;
    }

    try {
        $repositorio = new SeccionRepository();
        $existe = $repositorio->validar_codigo_seccion($codigo, $idExcluir);
        echo json_encode(['existe' => $existe]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}

function obtener_trayectos_secciones(): void {
    header('Content-Type: application/json; charset=utf-8');

    try {
        $repositorio = new SeccionRepository();
        $trayectos = $repositorio->obtener_trayectos();
        echo json_encode(['data' => $trayectos], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function obtener_secciones(): void {
    header('Content-Type: application/json; charset=utf-8');

    try {
        $repositorio = new SeccionRepository();
        $secciones = $repositorio->obtener_secciones();
        echo json_encode(['data' => $secciones], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

