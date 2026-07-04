<?php

use App\Trayectos\Repository\TrayectoRepository;

function consultar_trayectos(): void {
    require_once BASE_PATH . '/src/views/trayectos/consultar_trayectos.php';
}

function consultar_trayectos_data(): void {
    header('Content-Type: application/json; charset=utf-8');

    try {
        $repositorio = new TrayectoRepository();
        $data = $repositorio->consultar_con_fases();

        $trayectos = [];
        foreach ($data as $row) {
            $tid = $row['trayecto_id'];
            if (!isset($trayectos[$tid])) {
                $trayectos[$tid] = [
                    'trayecto' => $row['trayecto'],
                    'descripcion_trayecto' => $row['descripcion_trayecto'],
                    'fases' => []
                ];
            }
            if ($row['fase_id'] !== null) {
                $trayectos[$tid]['fases'][] = [
                    'fase' => $row['fase'],
                    'descripcion_fase' => $row['descripcion_fase']
                ];
            }
        }

        echo json_encode(['data' => array_values($trayectos)], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}
