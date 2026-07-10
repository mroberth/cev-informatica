<?php

use App\Inscripciones\Repository\InscripcionRepository;
use App\Inscripciones\Service\InscripcionService;

function crear_inscripciones(): void {
    require_once BASE_PATH . '/src/views/inscripciones/crear_inscripciones.php';
}

function consultar_inscripciones(): void {
    require_once BASE_PATH . '/src/views/inscripciones/consultar_inscripciones.php';
}

function obtener_periodos_inscripcion(): void {
    header('Content-Type: application/json; charset=utf-8');
    try {
        $repositorio = new InscripcionRepository();
        $periodos = $repositorio->obtener_periodos_activos();
        echo json_encode(['data' => $periodos], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function obtener_secciones_inscripcion(): void {
    header('Content-Type: application/json; charset=utf-8');
    $idPeriodo = (int) ($_GET['id_periodo'] ?? 0);
    if ($idPeriodo <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'ID de período inválido.']);
        exit;
    }
    try {
        $repositorio = new InscripcionRepository();
        $secciones = $repositorio->obtener_secciones_por_periodo($idPeriodo);
        echo json_encode(['data' => $secciones], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function obtener_datos_seccion_inscripcion(): void {
    header('Content-Type: application/json; charset=utf-8');
    $idSeccion = (int) ($_GET['id_seccion'] ?? 0);
    if ($idSeccion <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'ID de sección inválido.']);
        exit;
    }
    try {
        $repositorio = new InscripcionRepository();

        $seccion = $repositorio->obtener_seccion_por_id($idSeccion);
        if (!$seccion) {
            http_response_code(404);
            echo json_encode(['error' => 'Sección no encontrada.']);
            exit;
        }

        $inscritos = $repositorio->obtener_inscritos_por_seccion($idSeccion);
        $disponibles = $repositorio->obtener_estudiantes_disponibles($idSeccion);

        echo json_encode([
            'data' => [
                'seccion' => $seccion,
                'inscritos' => $inscritos,
                'disponibles' => $disponibles,
            ]
        ], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function guardar_inscripciones(): void {
    header('Content-Type: application/json; charset=utf-8');

    $input = json_decode(file_get_contents('php://input'), true);
    if (!is_array($input)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'error' => 'Datos inválidos.']);
        return;
    }

    try {
        $service = new InscripcionService();
        $data = $service->validarGuardar($input);

        $repositorio = new InscripcionRepository();

        if (!empty($data['id_estudiantes'])) {
            $yaInscritos = $repositorio->obtener_inscritos_por_ids($data['id_estudiantes']);
            if (!empty($yaInscritos)) {
                $nombres = array_map(fn($e) => "{$e['nombre']} {$e['apellido']} ({$e['tipo_cedula']}{$e['cedula']})", $yaInscritos);
                http_response_code(400);
                echo json_encode([
                    'status' => 'error',
                    'error' => 'Los siguientes estudiantes ya están inscritos en otra sección: ' . implode(', ', $nombres)
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }
        }

        $repositorio->guardar_inscripciones($data['id_seccion'], $data['id_estudiantes']);

        $seccion = $repositorio->obtener_seccion_por_id($data['id_seccion']);
        $nombreSeccion = $seccion['codigo_seccion'] ?? 'ID: ' . $data['id_seccion'];

        $totalEstudiantes = count($data['id_estudiantes']);
        registrar_en_bitacora(
            'CREAR',
            "Inscripciones guardadas para sección {$nombreSeccion} ({$totalEstudiantes} estudiantes)"
        );

        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'message' => 'Inscripciones guardadas correctamente.'
        ], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode(['status' => 'error', 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function consultar_inscripciones_data(): void {
    header('Content-Type: application/json; charset=utf-8');
    try {
        $repositorio = new InscripcionRepository();
        $inscripciones = $repositorio->consultar();
        echo json_encode(['data' => $inscripciones], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function eliminar_inscripcion(): void {
    header('Content-Type: application/json; charset=utf-8');

    $input = json_decode(file_get_contents('php://input'), true);
    $id = (int) ($input['id'] ?? 0);

    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'ID inválido.']);
        exit;
    }

    try {
        $repositorio = new InscripcionRepository();
        if ($repositorio->eliminar($id)) {
            registrar_en_bitacora('ELIMINAR', "Inscripción eliminada (ID: {$id})");
            echo json_encode(['status' => 'success', 'message' => 'Inscripción eliminada correctamente.'], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Inscripción no encontrada.']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}
