<?php
use App\Bitacora\Repository\BitacoraRepository;
use App\Bitacora\Service\BitacoraService;

function cargar_bitacora(): void {
    require_once BASE_PATH . "/src/views/bitacora/bitacora.php";
}

function obtener_bitacora(): void {
    header('Content-Type: application/json; charset=utf-8');

    $repositorio = new BitacoraRepository();
    $bitacora = $repositorio->consultar_bitacora();

    echo json_encode(['data' => $bitacora]);
    exit;
}

function registrar_bitacora(): void {
    header('Content-Type: application/json; charset=utf-8');

    $input = json_decode(file_get_contents('php://input'), true);

    if (!is_array($input)) {
        http_response_code(400);
        echo json_encode(['error' => 'Datos inválidos.']);
        return;
    }

    try {
        $service = new BitacoraService();
        $dto = $service->validarYPreparar($input);

        $repositorio = new BitacoraRepository();
        $registrado = $repositorio->registrar_bitacora($dto);

        if ($registrado) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Movimiento registrado en la bitácora.'
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'No se pudo registrar el movimiento.']);
        }
    } catch (Exception $e) {
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}

