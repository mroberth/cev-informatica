<?php require_once BASE_PATH . '/src/views/layouts/head.php'; ?>
<style>
:root {
  --sidebar-width: 250px;
  --header-height: 60px;
}
body {
  background: #f4f6f9;
  overflow-x: hidden;
}
.wrapper {
  display: flex;
  min-height: 100vh;
}

/* ===== SIDEBAR ===== */
.sidebar {
  width: var(--sidebar-width);
  background: #1a2a4a;
  color: #c8d6e5;
  display: flex;
  flex-direction: column;
  position: fixed;
  top: 0;
  left: 0;
  bottom: 0;
  z-index: 1030;
  transition: transform 0.3s;
}
.sidebar-brand {
  height: var(--header-height);
  display: flex;
  align-items: center;
  padding: 0 1.25rem;
  background: rgba(0,0,0,0.15);
  color: #fff;
  font-weight: 700;
  font-size: 1.1rem;
  flex-shrink: 0;
}
.sidebar-nav {
  flex: 1;
  overflow-y: auto;
  padding: 0.75rem 0;
}
.sidebar-nav .nav-item {
  margin: 0;
}
.sidebar-nav .nav-link {
  color: #c8d6e5;
  padding: 0.7rem 1.25rem;
  display: flex;
  align-items: center;
  gap: 0.75rem;
  font-size: 0.9rem;
  cursor: pointer;
  border: none;
  background: none;
  width: 100%;
  text-align: left;
  transition: background 0.2s;
}
.sidebar-nav .nav-link:hover,
.sidebar-nav .nav-link:focus {
  background: rgba(255,255,255,0.08);
  color: #fff;
}
.sidebar-nav .nav-link .chevron {
  margin-left: auto;
  transition: transform 0.25s;
}
.sidebar-nav .nav-link[aria-expanded="true"] .chevron {
  transform: rotate(90deg);
}
.sub-menu {
  background: rgba(0,0,0,0.12);
}
.sub-menu .nav-link {
  padding-left: 3.25rem;
  font-size: 0.85rem;
  color: #a0b4cc;
}
.sub-menu .nav-link:hover {
  color: #fff;
}

/* ===== CONTENIDO ===== */
.content {
  margin-left: var(--sidebar-width);
  flex: 1;
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

/* ===== HEADER ===== */
.top-header {
  height: var(--header-height);
  background: #fff;
  border-bottom: 1px solid #e9ecef;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 1.5rem;
  position: sticky;
  top: 0;
  z-index: 1020;
}
.top-header .brand-mobile {
  display: none;
  font-weight: 700;
  color: #1a2a4a;
}
.user-dropdown .dropdown-toggle {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  text-decoration: none;
  color: #495057;
  padding: 0.35rem 0.75rem;
  border-radius: 50px;
  transition: background 0.2s;
  cursor: pointer;
}
.user-dropdown .dropdown-toggle:hover {
  background: #f0f2f5;
}
.user-avatar {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background: #1a2a4a;
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  font-size: 0.9rem;
}

/* ===== MAIN ===== */
.main-content {
  flex: 1;
  padding: 2rem;
}
.main-content h4 {
  color: #1a2a4a;
  font-weight: 700;
}

/* ===== FOOTER ===== */
.footer-dashboard {
  background: #fff;
  border-top: 1px solid #e9ecef;
  padding: 1rem 1.5rem;
  text-align: center;
  font-size: 0.85rem;
  color: #6c757d;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
  .sidebar {
    transform: translateX(-100%);
  }
  .sidebar.open {
    transform: translateX(0);
  }
  .content {
    margin-left: 0;
  }
  .top-header .brand-mobile {
    display: block;
  }
  .sidebar-toggle {
    display: block !important;
  }
}
.sidebar-toggle {
  display: none;
  background: none;
  border: none;
  font-size: 1.5rem;
  color: #495057;
  cursor: pointer;
}
</style>
</head>
<body>

<div class="wrapper">

  <!-- ===== SIDEBAR ===== -->
  <aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
      <i class="bi bi-mortarboard-fill me-2"></i>CEV Informática
    </div>
    <nav class="sidebar-nav">
      <div class="nav-item">
        <button class="nav-link" data-bs-toggle="collapse" data-bs-target="#subUsuarios" aria-expanded="false">
          <i class="bi bi-people"></i>Usuarios
          <i class="bi bi-chevron-right chevron"></i>
        </button>
        <div class="collapse sub-menu" id="subUsuarios">
          <a class="nav-link" href="#"><i class="bi bi-plus-circle"></i>Crear</a>
          <a class="nav-link" href="#"><i class="bi bi-search"></i>Consultar</a>
        </div>
      </div>
      <div class="nav-item">
        <button class="nav-link" data-bs-toggle="collapse" data-bs-target="#subEstudiantes" aria-expanded="false">
          <i class="bi bi-mortarboard"></i>Estudiantes
          <i class="bi bi-chevron-right chevron"></i>
        </button>
        <div class="collapse sub-menu" id="subEstudiantes">
          <a class="nav-link" href="#"><i class="bi bi-plus-circle"></i>Crear</a>
          <a class="nav-link" href="#"><i class="bi bi-search"></i>Consultar</a>
        </div>
      </div>
      <div class="nav-item">
        <button class="nav-link" data-bs-toggle="collapse" data-bs-target="#subReportes" aria-expanded="false">
          <i class="bi bi-graph-up"></i>Reportes
          <i class="bi bi-chevron-right chevron"></i>
        </button>
        <div class="collapse sub-menu" id="subReportes">
          <a class="nav-link" href="#"><i class="bi bi-plus-circle"></i>Crear</a>
          <a class="nav-link" href="#"><i class="bi bi-search"></i>Consultar</a>
        </div>
      </div>
    </nav>
  </aside>

  <!-- ===== CONTENIDO PRINCIPAL ===== -->
  <div class="content">

    <!-- ===== HEADER ===== -->
    <header class="top-header">
      <div class="d-flex align-items-center gap-3">
        <button class="sidebar-toggle" id="sidebarToggle">
          <i class="bi bi-list"></i>
        </button>
        <span class="brand-mobile">CEV Informática</span>
      </div>
      <div class="dropdown user-dropdown" id="userDropdown">
        <div class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
          <div class="user-avatar" id="userAvatar">U</div>
          <span class="d-none d-md-inline" id="headerUserName">Usuario</span>
        </div>
        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
          <li><h6 class="dropdown-header" id="dropdownUserInfo">Usuario</h6></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Configurar perfil</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item text-danger" href="#" id="btnLogout"><i class="bi bi-box-arrow-right me-2"></i>Cerrar sesión</a></li>
        </ul>
      </div>
    </header>

    <!-- ===== MAIN ===== -->
    <main class="main-content">
      <div class="mb-4">
        <h4 class="mb-1"><span id="greetingRol">Bienvenido</span></h4>
        <p class="text-muted mb-0" id="greetingName"></p>
      </div>

      <div class="row g-4">
        <div class="col-12">
          <div class="card border-0 shadow-sm rounded-3">
            <div class="bg-primary" style="height: 4px; border-radius: 0.375rem 0.375rem 0 0;"></div>
            <div class="card-body p-4 text-center py-5">
              <i class="bi bi-house-door text-primary" style="font-size: 3rem;"></i>
              <p class="text-muted mt-3 mb-0">Panel principal — aquí irán los módulos del sistema.</p>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- ===== FOOTER ===== -->
    <footer class="footer-dashboard">
      &copy; 2026 CEV Informática — PNF en Informática. Todos los derechos reservados.
    </footer>

  </div>
</div>

<!-- ===== SCRIPTS ===== -->
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
  if (!token) { window.location.href = '/login'; return; }

  try {
    const payload = JSON.parse(atob(token.split('.')[1]));
    const nombre = localStorage.getItem('user_nombre') || '';
    const apellido = localStorage.getItem('user_apellido') || '';
    const rol = payload.user?.rol || localStorage.getItem('user_rol') || 'Usuario';
    const nombreCompleto = (nombre + ' ' + apellido).trim() || 'Usuario';
    const inicial = (nombre || 'U')[0].toUpperCase();

    document.getElementById('greetingRol').textContent = 'Bienvenido, ' + rol;
    document.getElementById('greetingName').textContent = nombreCompleto;
    document.getElementById('headerUserName').textContent = nombreCompleto;
    document.getElementById('userAvatar').textContent = inicial;
    document.getElementById('dropdownUserInfo').innerHTML = nombreCompleto + '<br><small class="text-muted">' + rol + '</small>';
  } catch {
    window.location.href = '/login';
  }
}

document.getElementById('btnLogout').addEventListener('click', async () => {
  try {
    await fetch(API_BASE + 'logout', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + obtenerToken() },
      body: JSON.stringify({ refresh_token: obtenerRefreshToken() })
    });
  } catch {}
  localStorage.clear();
  window.location.href = '/login';
});

document.getElementById('sidebarToggle').addEventListener('click', () => {
  document.getElementById('sidebar').classList.toggle('open');
});

document.addEventListener('DOMContentLoaded', mostrarInfoUsuario);
</script>
</body>
</html>
