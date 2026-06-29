<?php require_once BASE_PATH . '/src/views/layouts/head.php'; ?>
<body class="d-flex flex-column min-vh-100 bg-light">

  <main class="flex-grow-1 d-flex align-items-center py-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">

          <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="bg-primary" style="height: 5px;"></div>

            <div class="card-body p-4 p-md-5">
              <div class="text-center mb-4">
                <div class="mb-3">
                  <i class="bi bi-mortarboard-fill text-primary" style="font-size: 3rem;"></i>
                </div>
                <h3 class="fw-bold text-dark">Bienvenido de nuevo</h3>
                <p class="text-muted small">Ingresa tus credenciales institucionales para acceder al Control de Estudios Virtual.</p>
              </div>

              <form id="form-login" novalidate>
                <div class="mb-3">
                  <label for="email" class="form-label text-secondary small fw-bold">Correo electrónico</label>
                  <div class="input-group has-validation">
                    <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-envelope"></i></span>
                    <input type="email" class="form-control bg-white border-start-0 ps-0" id="correo" name="correo"
                      placeholder="ejemplo@correo.com">
                    <div class="invalid-feedback" id="correoError"></div>
                  </div>
                </div>

                <div class="mb-4">
                  <label for="password" class="form-label text-secondary small fw-bold">Contraseña</label>
                  <div class="input-group has-validation">
                    <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-lock"></i></span>
                    <input type="password" class="form-control bg-white border-start-0 border-end-0 ps-0" id="password"
                      name="password" placeholder="Ingresa tu contraseña">
                    <button class="input-group-text bg-white text-muted border-start-0" type="button" id="togglePassword" tabindex="-1">
                      <i class="bi bi-eye-slash" id="togglePasswordIcon"></i>
                    </button>
                    <div class="invalid-feedback" id="passwordError"></div>
                  </div>
                </div>

                <div class="d-grid gap-2">
                  <button type="submit" id="btn-login" class="btn btn-primary btn-lg fw-bold py-2 shadow-sm fs-6">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Ingresar al Sistema
                  </button>
                  <a href="/" class="btn btn-link btn-sm text-muted text-decoration-none mt-2">
                    <i class="bi bi-arrow-left me-1"></i> Volver al inicio
                  </a>
                </div>
              </form>
            </div>
          </div>

        </div>
      </div>
    </div>
  </main>

  <?php require_once BASE_PATH . '/src/views/layouts/footer.php'; ?>
  <script type="module" src="<?php echo BASE_URL; ?>js/modules/auth/loginController.js"></script>

  <script type="module">
      import { initLogin } from '<?php echo BASE_URL; ?>js/modules/auth/loginController.js';
      
      // Inicializamos los listeners del formulario
      document.addEventListener('DOMContentLoaded', () => {
          initLogin();
      });
  </script>
</body>
</html>
