<?php

use App\Profesor\Repository\ProfesorRepository;
use Core\Middleware\AuthMiddleware;

function profesor_dashboard(): void
{
    require_once BASE_PATH . '/src/views/profesor/dashboard.php';
}

function profesor_dashboard_data(): void
{
    header('Content-Type: application/json; charset=utf-8');

    try {
        $payload = AuthMiddleware::getUsuarioPayload();
        $idUsuario = (int) ($payload['sub'] ?? 0);
        if ($idUsuario <= 0) {
            throw new Exception('Usuario no autenticado', 401);
        }

        $repository = new ProfesorRepository();
        $docente = $repository->obtenerResumenDocente($idUsuario);
        $materias = $repository->obtenerMateriasAsignadas($idUsuario);

        echo json_encode([
            'data' => [
                'docente' => $docente,
                'total_materias' => count($materias),
                'total_recursos' => array_sum(array_column($materias, 'total_recursos')),
                'materias' => $materias,
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

function profesor_materias(): void
{
    require_once BASE_PATH . '/src/views/profesor/materias.php';
}

function profesor_materias_data(): void
{
    header('Content-Type: application/json; charset=utf-8');

    try {
        $payload = AuthMiddleware::getUsuarioPayload();
        $idUsuario = (int) ($payload['sub'] ?? 0);
        if ($idUsuario <= 0) {
            throw new Exception('Usuario no autenticado', 401);
        }

        $repository = new ProfesorRepository();
        $materias = $repository->obtenerMateriasAsignadas($idUsuario);

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

function profesor_materia(): void
{
    require_once BASE_PATH . '/src/views/profesor/materia.php';
}
