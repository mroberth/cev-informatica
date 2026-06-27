<?php

function cargar_controlador(string $modulo, string $archivo): void{
    static $cargados = [];

    // Construimos la ruta dinámica basada en la estructura modular del CEV
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