<?php
$titulo = $titulo ?? 'Error';
$mensaje = $mensaje ?? 'Ha ocurrido un error inesperado.';
$codigoHttp = $codigoHttp ?? 500;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?> - CEV Informática</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="d-flex flex-column min-vh-100 bg-light">
    <main class="flex-grow-1 d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5 text-center">
                    <div class="mb-4">
                        <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size: 4rem;"></i>
                    </div>
                    <h1 class="fw-bold text-dark mb-3"><?= $codigoHttp ?></h1>
                    <h5 class="text-muted mb-3"><?= $titulo ?></h5>
                    <p class="text-secondary mb-4"><?= htmlspecialchars($mensaje) ?></p>
                    <a href="/" class="btn btn-primary">
                        <i class="bi bi-house me-1"></i>Volver al inicio
                    </a>
                    <a href="/login" class="btn btn-outline-secondary ms-2">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Iniciar sesión
                    </a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
