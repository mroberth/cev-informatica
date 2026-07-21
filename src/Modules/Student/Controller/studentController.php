<?php

use App\Student\Repository\StudentRepository;
use Core\Middleware\AuthMiddleware;

function student_dashboard(): void {
    require_once BASE_PATH . '/src/views/student/dashboard.php';
}

function student_area_personal(): void {
    require_once BASE_PATH . '/src/views/student/dashboard.php';
}

function student_mis_cursos(): void {
    require_once BASE_PATH . '/src/views/student/mis_cursos.php';
}

function student_mis_cursos_data(): void {
    header('Content-Type: application/json; charset=utf-8');

    try {
        $payload = AuthMiddleware::getUsuarioPayload();
        $idUsuario = (int) ($payload['sub'] ?? 0);
        if ($idUsuario <= 0) {
            throw new Exception('Usuario no autenticado', 401);
        }

        $repository = new StudentRepository();
        $materias = $repository->obtenerMaterias($idUsuario);

        echo json_encode(['data' => $materias], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode([
            'status' => 'error',
            'error' => $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function student_dashboard_data(): void {
    header('Content-Type: application/json; charset=utf-8');

    try {
        $payload = AuthMiddleware::getUsuarioPayload();
        $idUsuario = (int) ($payload['sub'] ?? 0);
        if ($idUsuario <= 0) {
            throw new Exception('Usuario no autenticado', 401);
        }

        $repository = new StudentRepository();
        $trayecto = $repository->obtenerTrayectoActual($idUsuario);
        $materias = $repository->obtenerMaterias($idUsuario);

        echo json_encode([
            'data' => [
                'trayecto' => $trayecto['trayecto'] ?? null,
                'codigo_seccion' => $trayecto['codigo_seccion'] ?? null,
                'turno' => $trayecto['turno'] ?? null,
                'materias_count' => count($materias),
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
    exit;
}

function student_dashboard_proximas(): void
{
    header('Content-Type: application/json; charset=utf-8');

    try {
        $payload = AuthMiddleware::getUsuarioPayload();
        $idUsuario = (int) ($payload['sub'] ?? 0);
        if ($idUsuario <= 0) throw new Exception('Usuario no autenticado', 401);

        $repository = new StudentRepository();
        $evaluaciones = $repository->obtenerProximasEvaluaciones($idUsuario);
        $resumenNotas = $repository->obtenerResumenNotas($idUsuario);

        $colores = [
            'tarea' => '#0d6efd',
            'examen' => '#dc3545',
            'proyecto' => '#198754',
            'taller' => '#fd7e14',
            'otro' => '#6f42c1',
        ];

        $proximas = array_map(function ($e) use ($colores) {
            $dias = (int) ($e['dias_restantes'] ?? 0);
            $tieneNota = $e['calificacion_id'] !== null;
            $vencida = $dias < 0 && !$tieneNota;
            $proxima = $dias >= 0 && $dias <= 3 && !$tieneNota;

            if ($vencida) $urg = 'vencida';
            elseif ($proxima) $urg = 'proxima';
            elseif ($tieneNota) $urg = 'completada';
            else $urg = 'pendiente';

            return [
                'id' => (int) $e['id'],
                'titulo' => $e['titulo'],
                'tipo' => $e['tipo'],
                'porcentaje' => $e['porcentaje'] ? (float) $e['porcentaje'] : null,
                'fecha_estimada' => $e['fecha_estimada'],
                'dias_restantes' => $dias,
                'materia_nombre' => $e['materia_nombre'],
                'materia_codigo' => $e['materia_codigo'],
                'materia_id' => (int) $e['materia_id'],
                'nota' => $e['nota'] !== null ? (float) $e['nota'] : null,
                'urgencia' => $urg,
                'color' => $colores[$e['tipo']] ?? $colores['otro'],
            ];
        }, $evaluaciones);

        echo json_encode([
            'data' => [
                'proximas' => $proximas,
                'resumen_notas' => $resumenNotas,
            ]
        ], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode(['status' => 'error', 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}
