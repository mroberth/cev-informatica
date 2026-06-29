<?php require_once BASE_PATH . '/src/views/layouts/head.php'; ?>
<body class="d-flex flex-column min-vh-100 bg-light">

  <nav class="navbar navbar-dark bg-primary shadow-sm">
    <div class="container">
      <a class="navbar-brand fw-bold" href="/">
        <i class="bi bi-mortarboard-fill me-2"></i>CEV - Informática
      </a>
      <div class="d-flex align-items-center gap-3">
        <span class="text-white small" id="userDisplay">Conectado</span>
        <button id="btnLogout" class="btn btn-outline-light btn-sm">
          <i class="bi bi-box-arrow-right me-1"></i>Cerrar sesión
        </button>
      </div>
    </div>
  </nav>

  <main class="flex-grow-1">
    <div class="container py-5">
      <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
          <div class="card border-0 shadow-sm rounded-3">
            <div class="bg-primary" style="height: 5px;"></div>
            <div class="card-body p-4 p-md-5 text-center">
              <div class="mb-4">
                <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
              </div>
              <h3 class="fw-bold text-dark mb-2">Inicio de sesión exitoso</h3>
              <p class="text-muted mb-1">Bienvenido al sistema, <strong id="userName">Usuario</strong></p>
              <p class="text-muted small mb-0" id="userEmail"></p>
              <p class="text-muted small mb-0" id="userRol"></p>
              <hr class="my-4">
              <p class="text-secondary small">
                Esta es una vista temporal. El dashboard completo estará disponible próximamente.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <?php require_once BASE_PATH . '/src/views/layouts/footer.php'; ?>

  <script type="module">
    const API_BASE = '/';

    function obtenerToken() {
      return localStorage.getItem('token');
    }

    function obtenerRefreshToken() {
      return localStorage.getItem('refresh_token');
    }

    function mostrarInfoUsuario() {
      const token = obtenerToken();
      if (!token) {
        window.location.href = '/login';
        return;
      }

      try {
        const payload = JSON.parse(atob(token.split('.')[1]));
        document.getElementById('userName').textContent = 
          (localStorage.getItem('user_nombre') || '') + ' ' + (localStorage.getItem('user_apellido') || '');
        document.getElementById('userEmail').textContent = 'Correo: ' + (payload.user?.correo || localStorage.getItem('user_email') || '');
        document.getElementById('userRol').textContent = 'Rol: ' + (payload.user?.rol || localStorage.getItem('user_rol') || '');
        document.getElementById('userDisplay').textContent = 'Bienvenido, ' + (localStorage.getItem('user_nombre') || '');
      } catch {
        window.location.href = '/login';
      }
    }

    document.getElementById('btnLogout').addEventListener('click', async () => {
      const refreshToken = obtenerRefreshToken();

      try {
        await fetch(API_BASE + 'logout', {
          method: 'POST',
          headers: { 
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + obtenerToken()
          },
          body: JSON.stringify({ refresh_token: refreshToken })
        });
      } catch {
        // Continúa aunque falle la llamada
      }

      localStorage.clear();
      window.location.href = '/login';
    });

    document.addEventListener('DOMContentLoaded', mostrarInfoUsuario);
  </script>
</body>
</html>
