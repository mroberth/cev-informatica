<?php

use App\AsignacionDocente\DTO\AsignacionDocenteDTO;
use App\AsignacionDocente\Repository\AsignacionDocenteRepository;
use App\AsignacionDocente\Service\AsignacionDocenteService;

function crear_asignacion_docente(): void {
    require_once BASE_PATH . '/src/views/asignacion_docente/crear_asignacion_docente.php';
}

function consultar_asignacion_docente(): void {
    require_once BASE_PATH . '/src/views/asignacion_docente/consultar_asignacion_docente.php';
}

function obtener_periodos_asignacion(): void {
    header('Content-Type: application/json; charset=utf-8');
    try {
        $repositorio = new AsignacionDocenteRepository();
        $periodos = $repositorio->obtener_periodos_activos();
        echo json_encode(['data' => $periodos], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function obtener_secciones_por_periodo_asignacion(): void {
    header('Content-Type: application/json; charset=utf-8');
    $idPeriodo = (int) ($_GET['id_periodo'] ?? 0);
    if ($idPeriodo <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'ID de período inválido.']);
        exit;
    }
    try {
        $repositorio = new AsignacionDocenteRepository();
        $secciones = $repositorio->obtener_secciones_por_periodo($idPeriodo);
        echo json_encode(['data' => $secciones], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function obtener_docentes_asignacion(): void {
    header('Content-Type: application/json; charset=utf-8');
    try {
        $repositorio = new AsignacionDocenteRepository();
        $docentes = $repositorio->obtener_docentes_activos();
        echo json_encode(['data' => $docentes], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function obtener_datos_seccion_asignacion(): void {
    header('Content-Type: application/json; charset=utf-8');
    $idSeccion = (int) ($_GET['id_seccion'] ?? 0);
    if ($idSeccion <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'ID de sección inválido.']);
        exit;
    }
    try {
        $repositorio = new AsignacionDocenteRepository();

        $seccion = $repositorio->obtener_seccion_por_id($idSeccion);
        if (!$seccion) {
            http_response_code(404);
            echo json_encode(['error' => 'Sección no encontrada.']);
            exit;
        }

        $ucs = $repositorio->obtener_uc_por_trayecto((int) $seccion['id_trayecto']);
        $asignaciones = $repositorio->obtener_por_seccion($idSeccion);

        $asignacionesMap = [];
        foreach ($asignaciones as $asig) {
            $asignacionesMap[(int) $asig['id_unidad_curricular']] = (int) $asig['id_docente'];
        }

        echo json_encode([
            'data' => [
                'seccion' => $seccion,
                'ucs' => $ucs,
                'asignaciones' => $asignacionesMap,
            ]
        ], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function guardar_asignaciones(): void {
    header('Content-Type: application/json; charset=utf-8');

    $input = json_decode(file_get_contents('php://input'), true);
    if (!is_array($input)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'error' => 'Datos inválidos.']);
        return;
    }

    try {
        $service = new AsignacionDocenteService();
        $data = $service->validarGuardar($input);

        $repositorio = new AsignacionDocenteRepository();
        $repositorio->guardar_asignaciones($data['id_seccion'], $data['asignaciones']);

        $seccion = $repositorio->obtener_seccion_por_id($data['id_seccion']);
        $nombreSeccion = $seccion['codigo_seccion'] ?? 'ID: ' . $data['id_seccion'];

        registrar_en_bitacora(
            'CREAR',
            "Asignaciones guardadas para sección {$nombreSeccion}"
        );

        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'message' => 'Asignaciones guardadas correctamente.'
        ], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode(['status' => 'error', 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function consultar_asignaciones_data(): void {
    header('Content-Type: application/json; charset=utf-8');
    try {
        $repositorio = new AsignacionDocenteRepository();
        $asignaciones = $repositorio->consultar();
        echo json_encode(['data' => $asignaciones], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function eliminar_asignacion(): void {
    header('Content-Type: application/json; charset=utf-8');

    $input = json_decode(file_get_contents('php://input'), true);
    $id = (int) ($input['id'] ?? 0);

    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'ID inválido.']);
        exit;
    }

    try {
        $repositorio = new AsignacionDocenteRepository();
        if ($repositorio->eliminar($id)) {
            registrar_en_bitacora('ELIMINAR', "Asignación docente eliminada (ID: {$id})");
            echo json_encode(['status' => 'success', 'message' => 'Asignación eliminada correctamente.'], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Asignación no encontrada.']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}
