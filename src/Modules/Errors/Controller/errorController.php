<?php

function error_404(): void{
    require_once BASE_PATH . '/src/views/errors/404.php';
}

function error_405(): void{
    require_once BASE_PATH . '/src/views/errors/405.php';
}
