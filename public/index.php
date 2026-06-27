<?php
ob_start();
define('BASE_PATH', dirname(__DIR__, 1));
define('BASE_URL', '/');

//================== CORS MIDDLEWARE GLOBAL ==================//
$origen = $_SERVER['HTTP_ORIGIN'] ?? '';
$origenesPermitidos = [
    'http://localhost:8080',
    'http://localhost:8100',
    'http://localhost:3000',
    'http://localhost:5173',
];

if (in_array($origen, $origenesPermitidos)) {
    header("Access-Control-Allow-Origin: " . $origen);
    header("Access-Control-Allow-Credentials: true");
}

header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, Accept, X-Requested-With");
header("Access-Control-Max-Age: 86400");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// =======================================================
session_start();

require_once BASE_PATH . '/vendor/autoload.php';
\Core\Exception\ManejadorErrorGlobal::registrar();
\Core\Config\DotEnv::cargar(BASE_PATH . '/.env');
require_once BASE_PATH . '/src/Modules/rutas.php';

\Core\Http\Router::ejecutar();