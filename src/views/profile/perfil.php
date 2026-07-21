<?php

use Core\Middleware\AuthMiddleware;

$payload = AuthMiddleware::getUsuarioPayload();
$user = null;
if (is_array($payload)) {
    $user = $payload['user'] ?? null;
} elseif (is_object($payload)) {
    $user = $payload->user ?? null;
}
$rol = '';
if (is_array($user)) {
    $rol = $user['rol'] ?? $user['nombre_rol'] ?? '';
} elseif (is_object($user)) {
    $rol = $user->rol ?? $user->nombre_rol ?? '';
}

$pageStyles = ['/css/modules/perfil/perfil.css'];
$pageModuleScripts = ['/js/modules/perfil/perfil.js'];
$contentView = 'profile/perfil_content.php';

if (in_array($rol, ['Admin', 'Superusuario'])) {
    require_once BASE_PATH . '/src/views/layouts/admin.php';
} elseif ($rol === 'Profesor') {
    require_once BASE_PATH . '/src/views/layouts/profesor.php';
} else {
    require_once BASE_PATH . '/src/views/layouts/student.php';
}
