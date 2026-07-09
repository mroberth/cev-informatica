<?php

use App\UnidadesCurriculares\DTO\UnidadCurricularDTO;
use App\UnidadesCurriculares\Repository\UnidadCurricularRepository;
use App\UnidadesCurriculares\Service\UnidadCurricularService;

function crear_unidad_curricular(): void {
    require_once BASE_PATH . '/src/views/unidades_curriculares/crear_unidades_curriculares.php';
}

function consultar_unidades_curriculares(): void {
    require_once BASE_PATH . '/src/views/unidades_curriculares/consultar_unidades_curriculares.php';
}

function obtener_trayectos(): void {
    header('Content-Type: application/json; charset=utf-8');
    $repositorio = new UnidadCurricularRepository();
    echo json_encode(['data' => $repositorio->obtener_trayectos()]);
    exit;
}

function obtener_fases(): void {
    header('Content-Type: application/json; charset=utf-8');
    $idTrayecto = (int) ($_GET['id_trayecto'] ?? 0);
    if ($idTrayecto <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'ID de trayecto inválido.']);
        exit;
    }
    $repositorio = new UnidadCurricularRepository();
    echo json_encode(['data' => $repositorio->obtener_fases_por_trayecto($idTrayecto)]);
    exit;
}

function verificar_codigo_uc(): void {
    header('Content-Type: application/json; charset=utf-8');
    $codigo = $_GET['codigo'] ?? '';
    $idExcluir = isset($_GET['id_excluir']) ? (int) $_GET['id_excluir'] : null;
    if (empty($codigo)) {
        http_response_code(400);
        echo json_encode(['error' => 'El código es obligatorio.']);
        exit;
    }
    $repositorio = new UnidadCurricularRepository();
    echo json_encode(['existe' => $repositorio->validar_codigo($codigo, $idExcluir)]);
    exit;
}

function registrar_unidad_curricular(): void {
    header('Content-Type: application/json; charset=utf-8');
    $input = json_decode(file_get_contents('php://input'), true);
    if (!is_array($input)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'error' => 'Datos inválidos.']);
        return;
    }

    $fases = array_map('intval', $input['fases'] ?? []);

    $ucDTO = new UnidadCurricularDTO(
        0,
        $fases,
        (string) ($input['codigo'] ?? ''),
        (string) ($input['nombre'] ?? ''),
        (int) ($input['unidades_credito'] ?? 0),
    );

    try {
        $service = new UnidadCurricularService();
        $repositorio = new UnidadCurricularRepository();
        $ucValidado = $service->validar($ucDTO);

        if ($repositorio->validar_codigo($ucValidado->getCodigo())) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'error' => 'El código ingresado ya existe.']);
            return;
        }

        $idInsertado = $repositorio->registrar($ucValidado);

        registrar_en_bitacora(
            'CREAR',
            "Unidad Curricular creada: {$ucValidado->getCodigo()} - {$ucValidado->getNombre()} (ID: {$idInsertado})"
        );

        http_response_code(201);
        echo json_encode([
            'status' => 'success',
            'data' => ['id' => $idInsertado, 'message' => 'Unidad Curricular registrada correctamente.']
        ], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode(['status' => 'error', 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
}

function consultar_uc_data(): void {
    header('Content-Type: application/json; charset=utf-8');
    try {
        $repositorio = new UnidadCurricularRepository();
        echo json_encode(['data' => $repositorio->consultar()]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}

function obtener_unidad_curricular(): void {
    header('Content-Type: application/json; charset=utf-8');
    $id = (int) ($_GET['id'] ?? 0);
    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'ID inválido.']);
        exit;
    }
    $repositorio = new UnidadCurricularRepository();
    $uc = $repositorio->obtener_por_id($id);
    if (!$uc) {
        http_response_code(404);
        echo json_encode(['error' => 'Unidad Curricular no encontrada.']);
        exit;
    }
    echo json_encode(['data' => $uc]);
    exit;
}

function actualizar_unidad_curricular(): void {
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

    $fases = array_map('intval', $input['fases'] ?? []);

    $ucDTO = new UnidadCurricularDTO(
        $id,
        $fases,
        (string) ($input['codigo'] ?? ''),
        (string) ($input['nombre'] ?? ''),
        (int) ($input['unidades_credito'] ?? 0),
    );

    try {
        $service = new UnidadCurricularService();
        $repositorio = new UnidadCurricularRepository();
        $ucValidado = $service->validar($ucDTO);

        if ($repositorio->validar_codigo($ucValidado->getCodigo(), $id)) {
            http_response_code(400);
            echo json_encode(['error' => 'El código ingresado ya existe en otra unidad curricular.']);
            return;
        }

        $repositorio->actualizar($ucValidado);

        registrar_en_bitacora(
            'ACTUALIZAR',
            "Unidad Curricular actualizada: {$ucValidado->getCodigo()} - {$ucValidado->getNombre()} (ID: {$id})"
        );

        echo json_encode(['status' => 'success', 'message' => 'Unidad Curricular actualizada correctamente.']);
    } catch (Exception $e) {
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}
