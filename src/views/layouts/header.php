<!-- ===== HEADER ===== -->
    <header class="top-header">
      <div class="d-flex align-items-center gap-3">
        <button class="sidebar-toggle" id="botonMenuSidebar">
          <i class="bi bi-list"></i>
        </button>
        <span class="brand-mobile">CEV Informática</span>
      </div>
      <div class="d-flex align-items-center gap-2">
        <div class="dropdown" id="notificacionesDropdown">
          <button class="btn position-relative" id="notificationBell" title="Notificaciones" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-bell fs-5"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notificationBadge" style="font-size:0.6rem; display:none;">0</span>
          </button>
          <div class="dropdown-menu dropdown-menu-end shadow-sm" id="notificacionesPanel" style="width:360px; max-height:400px; overflow-y:auto;">
            <div class="dropdown-header d-flex justify-content-between align-items-center">
              <strong>Notificaciones</strong>
              <button class="btn btn-sm btn-link p-0" id="marcarTodasLeidas">Marcar todas leídas</button>
            </div>
            <div id="notificacionesLista">
              <p class="text-muted text-center small my-3">Cargando...</p>
            </div>
          </div>
        </div>
        <div class="dropdown user-dropdown" id="userDropdown">
        <div class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
          <div class="user-avatar" id="avatarUsuario"></div>
          <span class="d-none d-md-inline" id="nombreUsuarioCabecera"></span>
        </div>
        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
          <li><h6 class="dropdown-header" id="informacionUsuarioDesplegable"></h6></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item" href="/a/perfil"><i class="bi bi-person me-2"></i>Configurar perfil</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item text-danger" href="#" id="botonCerrarSesion"><i class="bi bi-box-arrow-right me-2"></i>Cerrar sesión</a></li>
        </ul>
      </div>
    </header>