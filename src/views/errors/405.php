<?php require_once BASE_PATH . '/src/views/layouts/head.php'; ?>
<?php $inicioUrl = function_exists('url_inicio_error') ? url_inicio_error() : '/'; ?>
<body class="bg-white">
  <div class="d-flex align-items-center justify-content-center min-vh-100 bg-white">
    <div class="text-center p-4">

      <div class="mb-4">
        <i class="bi bi-mortarboard text-primary" style="font-size: 4rem;"></i>
      </div>

      <h1 class="display-1 fw-bold text-primary mb-0">405</h1>
      <h2 class="fw-bold text-dark mt-2 mb-3">Método no permitido</h2>

      <p class="text-muted mb-4" style="max-width: 400px; margin: 0 auto;">
        El método HTTP utilizado no está permitido para esta ruta. Verifica que estás usando el método correcto (GET, POST, PUT, DELETE) o contacta al administrador.
      </p>

      <div class="d-flex justify-content-center gap-2">
        <a href="<?= $inicioUrl ?>" class="btn btn-primary fw-bold px-4">
          <i class="bi bi-house-door me-1"></i> Ir al Inicio
        </a>
        <button onclick="window.history.back()" class="btn btn-outline-secondary fw-bold px-4">
          <i class="bi bi-arrow-left me-1"></i> Regresar
        </button>
      </div>

    </div>
  </div>
<?php require_once BASE_PATH . '/src/views/layouts/footer.php'; ?>
</body>
</html>
