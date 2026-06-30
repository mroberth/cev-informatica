<!-- ===== HEADER ===== -->
    <header class="top-header">
      <div class="d-flex align-items-center gap-3">
        <button class="sidebar-toggle" id="botonMenuSidebar">
          <i class="bi bi-list"></i>
        </button>
        <span class="brand-mobile">CEV Informática</span>
      </div>
      <div class="dropdown user-dropdown" id="userDropdown">
        <div class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
          <div class="user-avatar" id="avatarUsuario"></div>
          <span class="d-none d-md-inline" id="nombreUsuarioCabecera"></span>
        </div>
        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
          <li><h6 class="dropdown-header" id="informacionUsuarioDesplegable"></h6></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Configurar perfil</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item text-danger" href="#" id="botonCerrarSesion"><i class="bi bi-box-arrow-right me-2"></i>Cerrar sesión</a></li>
        </ul>
      </div>
    </header>