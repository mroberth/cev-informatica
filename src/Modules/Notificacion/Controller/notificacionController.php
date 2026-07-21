<?php

use App\Notificacion\Repository\NotificacionRepository;
use Core\Middleware\AuthMiddleware;

function notification_stream(): void
{
    session_write_close();

    header('Content-Type: text/event-stream');
    header('Cache-Control: no-cache');
    header('Connection: keep-alive');
    header('X-Accel-Buffering: no');

    $payload = AuthMiddleware::getUsuarioPayload();
    $idUsuario = (int) ($payload['sub'] ?? 0);
    if ($idUsuario <= 0) {
        echo "event: error\ndata: No autenticado\n\n";
        ob_flush();
        flush();
        exit;
    }

    $repository = new NotificacionRepository();
    $ultimoId = 0;

    $existing = $repository->obtenerNoLeidas($idUsuario);
    if (!empty($existing)) {
        $count = count($existing);
        echo "event: notifications\ndata: " . json_encode(['count' => $count, 'list' => $existing]) . "\n\n";
        ob_flush();
        flush();
        $ultimoId = max(array_column($existing, 'id'));
    }

    $maxDuration = 300;
    $start = time();

    while ((time() - $start) < $maxDuration) {
        $nuevas = $repository->notificacionesDesde($idUsuario, $ultimoId);
        if (!empty($nuevas)) {
            $count = count($nuevas);
            $ultimoId = max(array_column($nuevas, 'id'));
            echo "event: notifications\ndata: " . json_encode(['count' => $count, 'list' => $nuevas]) . "\n\n";
            ob_flush();
            flush();
        } else {
            echo ": heartbeat\n\n";
            ob_flush();
            flush();
        }

        if (connection_aborted()) break;
        sleep(3);
    }
}

function notification_listar(): void
{
    header('Content-Type: application/json; charset=utf-8');

    try {
        $payload = AuthMiddleware::getUsuarioPayload();
        $idUsuario = (int) ($payload['sub'] ?? 0);
        if ($idUsuario <= 0) throw new Exception('No autenticado', 401);

        $repository = new NotificacionRepository();
        $notificaciones = $repository->obtenerUltimas($idUsuario);
        $noLeidas = $repository->contarNoLeidas($idUsuario);

        echo json_encode(['data' => $notificaciones, 'no_leidas' => $noLeidas], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode(['status' => 'error', 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function notification_marcar_leida(): void
{
    header('Content-Type: application/json; charset=utf-8');

    try {
        $payload = AuthMiddleware::getUsuarioPayload();
        $idUsuario = (int) ($payload['sub'] ?? 0);
        if ($idUsuario <= 0) throw new Exception('No autenticado', 401);

        $idNotificacion = extraerIdUrl();
        if ($idNotificacion <= 0) throw new Exception('ID inválido.', 400);

        $repository = new NotificacionRepository();
        $repository->marcarLeida($idNotificacion, $idUsuario);

        echo json_encode(['status' => 'ok'], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode(['status' => 'error', 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function notification_marcar_todas_leidas(): void
{
    header('Content-Type: application/json; charset=utf-8');

    try {
        $payload = AuthMiddleware::getUsuarioPayload();
        $idUsuario = (int) ($payload['sub'] ?? 0);
        if ($idUsuario <= 0) throw new Exception('No autenticado', 401);

        $repository = new NotificacionRepository();
        $repository->marcarTodasLeidas($idUsuario);

        echo json_encode(['status' => 'ok'], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode(['status' => 'error', 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}
