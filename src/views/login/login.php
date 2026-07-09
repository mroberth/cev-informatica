<?php require_once BASE_PATH . '/src/views/layouts/head.php'; ?>
<body class="d-flex flex-column min-vh-100 cev-login-page">

  <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/modules/login/login.css">

  <main class="flex-grow-1 cev-login-shell">
    <div class="container-fluid px-0">
      <div class="row g-0 min-vh-100">
        <section class="col-lg-8 d-flex align-items-center justify-content-center px-3 px-md-5 py-5 cev-login-panel cev-login-panel--form">
          <div class="cev-form-card">
            <div class="cev-form-backdrop p-4 p-md-5">
              <div class="d-flex align-items-center gap-3 mb-4">
                <div class="rounded-4 d-inline-flex align-items-center justify-content-center" style="width: 3.25rem; height: 3.25rem; background: linear-gradient(135deg, rgba(37, 212, 199, 0.14), rgba(93, 167, 255, 0.16)); color: #0d4f6f;">
                  <i class="bi bi-cpu-fill fs-4"></i>
                </div>
                <div>
                  <div class="cev-form-kicker mb-1">
                    <i class="bi bi-shield-lock-fill"></i>
                    Acceso institucional
                  </div>
                  <p class="mb-0 small text-uppercase fw-semibold" style="letter-spacing: 0.12em; color: #6a7e98;">CEV | Centro de Estudios Virtual</p>
                </div>
              </div>

              <div class="mb-4">
                <h1 class="h2 fw-bold cev-login-title mb-2">Acceso al CEV</h1>
                <p class="cev-login-subtitle mb-0">Ingresa con tus credenciales institucionales para continuar en el sistema académico de Informática.</p>
              </div>

              <?php if(isset($_SESSION['error_auth'])): ?>
                <div class="alert cev-alert-auth d-flex align-items-start gap-2 mb-4" role="alert">
                  <i class="bi bi-shield-exclamation fs-5 mt-0"></i>
                  <div class="flex-grow-1"><?= htmlspecialchars($_SESSION['error_auth']) ?></div>
                  <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
              <?php unset($_SESSION['error_auth']); endif; ?>

              <form id="form-login" novalidate>
                <div class="cev-field">
                  <label for="email" class="form-label small fw-bold text-uppercase" style="letter-spacing: 0.08em; color: #5a6c84;">Correo electrónico</label>
                  <div class="input-group cev-input-group has-validation">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" class="form-control" id="correo" name="correo" placeholder="ejemplo@correo.com">
                  </div>
                  <div class="invalid-feedback cev-field-feedback d-block" id="correoError"></div>
                </div>

                <div class="cev-field mb-4">
                  <label for="password" class="form-label small fw-bold text-uppercase" style="letter-spacing: 0.08em; color: #5a6c84;">Contraseña</label>
                  <div class="input-group cev-input-group has-validation">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Ingresa tu contraseña">
                    <button class="btn cev-toggle-btn" type="button" id="togglePassword" tabindex="-1" aria-label="Mostrar u ocultar contraseña">
                      <i class="bi bi-eye-slash" id="togglePasswordIcon"></i>
                    </button>
                  </div>
                  <div class="invalid-feedback cev-field-feedback d-block" id="passwordError"></div>
                </div>

                <div class="d-grid gap-3 mt-4">
                  <button type="submit" id="btn-login" class="btn btn-primary btn-lg fw-semibold cev-btn-login fs-6">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Ingresar al Sistema
                  </button>
                  <a href="/" class="btn btn-link btn-sm text-decoration-none cev-btn-back align-self-center">
                    <i class="bi bi-arrow-left me-1"></i> Volver al inicio
                  </a>
                </div>
              </form>
            </div>
          </div>
        </section>

        <aside class="col-lg-4 d-flex align-items-center justify-content-center px-3 px-md-5 py-5 cev-login-panel cev-login-panel--tech">
          <div class="cev-tech-content w-100" style="max-width: 24rem;">
            <div class="mb-4">
              <span class="cev-tech-badge mb-3">
                <i class="bi bi-terminal-fill"></i>
                Entorno institucional
              </span>
              <h2 class="h3 fw-bold mb-3" style="letter-spacing: -0.03em;">Sistema académico seguro y confiable.</h2>
              <p class="mb-0" style="color: rgba(232, 241, 255, 0.72);">Una composición discreta, alineada con la identidad institucional del CEV y centrada en el acceso.</p>
            </div>

            <div class="cev-code-window mb-4">
              <div class="cev-code-window__bar">
                <span class="cev-code-window__dot cev-code-window__dot--red"></span>
                <span class="cev-code-window__dot cev-code-window__dot--amber"></span>
                <span class="cev-code-window__dot cev-code-window__dot--green"></span>
              </div>
              <div class="cev-code-window__body">
                <span class="cev-code-line"><span class="cev-code-comment">// CEV Informática</span></span>
                <span class="cev-code-line"><span class="cev-code-keyword">acceso</span> = <span class="cev-code-string">'institucional'</span>;</span>
                <span class="cev-code-line"><span class="cev-code-keyword">estado</span> = <span class="cev-code-accent">autenticación segura</span>;</span>
                <span class="cev-code-line"><span class="cev-code-keyword">rol</span> = <span class="cev-code-string">'estudiante / docente / admin'</span>;</span>
                <span class="cev-code-line"><span class="cev-code-keyword">sistema</span> = <span class="cev-code-accent">CEV</span>;</span>
              </div>
            </div>

            <div class="row g-3">
              <div class="col-sm-6">
                <div class="p-3 rounded-4 h-100" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.08);">
                  <div class="d-flex align-items-center gap-2 mb-2" style="color: #8fe7de;">
                    <i class="bi bi-building"></i>
                    <span class="fw-semibold">Institucional</span>
                  </div>
                  <p class="mb-0 small" style="color: rgba(232, 241, 255, 0.7);">Estética sobria y alineada a la identidad del centro académico.</p>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="p-3 rounded-4 h-100" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.08);">
                  <div class="d-flex align-items-center gap-2 mb-2" style="color: #8fb7ff;">
                    <i class="bi bi-shield-check"></i>
                    <span class="fw-semibold">Acceso seguro</span>
                  </div>
                  <p class="mb-0 small" style="color: rgba(232, 241, 255, 0.7);">El panel acompaña sin competir con el formulario principal.</p>
                </div>
              </div>
            </div>
          </div>
        </aside>
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
