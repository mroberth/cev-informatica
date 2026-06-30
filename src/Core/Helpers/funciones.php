<?php

function cargar_controlador(string $modulo, string $archivo): void{
    static $cargados = [];

    $ruta = rtrim(BASE_PATH, '/\\') . "/src/Modules/{$modulo}/Controller/{$archivo}";

    if (isset($cargados[$ruta])) {
        return;
    }

    if (is_readable($ruta)) {
        require_once $ruta;
        $cargados[$ruta] = true;
        return;
    }

    throw new \RuntimeException("Controlador modular no encontrado: {$ruta}");
}

function es_json(): bool{
    return (
        (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') ||
        (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) ||
        (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) ||
        (isset($_SERVER['HTTP_SEC_FETCH_DEST']) && $_SERVER['HTTP_SEC_FETCH_DEST'] === 'empty')
    );
}

function mensaje_error_http(int $codigo): string{
    return match($codigo){
        400 => 'Solicitud inválida. Verifica los datos enviados.',
        401 => 'Debes iniciar sesión para acceder a esta sección.',
        403 => 'No tienes permisos para realizar esta acción.',
        404 => 'La página que buscas no existe.',
        405 => 'Método no permitido.',
        419 => 'La sesión ha expirado. Inicia sesión nuevamente.',
        422 => 'Los datos enviados no son válidos.',
        429 => 'Demasiadas peticiones. Espera unos segundos e inténtalo de nuevo.',
        500 => 'Error interno del servidor. Intenta más tarde.',
        503 => 'Servicio no disponible. El servidor está en mantenimiento.',
        default => 'Ha ocurrido un error inesperado.'
    };
}

function url_inicio_error(): string{
    if(class_exists('Core\\Middleware\\AuthMiddleware')){
        $payload = \Core\Middleware\AuthMiddleware::getUsuarioPayload();
        if(!empty($payload)){
            $user = null;
            if(is_array($payload)){
                $user = $payload['user'] ?? null;
            } elseif(is_object($payload)){
                $user = $payload->user ?? null;
            }

            if(is_array($user)){
                $rol = strtolower($user['rol'] ?? $user['nombre_rol'] ?? '');
            } elseif(is_object($user)){
                $rol = strtolower($user->rol ?? $user->nombre_rol ?? '');
            } else{
                $rol = '';
            }

            return $rol === 'admin' ? '/a/dashboard' : '/u/dashboard';
        }
    }

    return '/';
}

function responder_error(int $codigo): void{
    http_response_code($codigo);
    $mensaje = mensaje_error_http($codigo);

    if(es_json()){
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error',
            'message' => $mensaje,
            'code' => $codigo
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $titulo = match($codigo){
        401 => 'Acceso no autorizado',
        403 => 'Acceso denegado',
        404 => 'Página no encontrada',
        405 => 'Método no permitido',
        429 => 'Demasiadas peticiones',
        500 => 'Error interno del servidor',
        503 => 'Servicio no disponible',
        default => 'Error'
    };

    if($codigo === 401){
        $_SESSION['error_auth'] = $mensaje;
        header('Location: /login');
        exit;
    }

    require_once BASE_PATH . '/src/views/errors/error_general.php';
    exit;
}
