<?php
$pageStyles = $pageStyles ?? [];
$pageStyles[] = '/css/modules/profesor/profesor.css';
$pageScripts = $pageScripts ?? [];
$pageModuleScripts = $pageModuleScripts ?? [];
$contentView = $contentView ?? null;

require_once BASE_PATH . '/src/views/layouts/head.php';
?>
<body>

<nav class="cev-prof-navbar">
  <div class="cev-prof-navbar-inner">
    <div class="cev-prof-navbar-start">
      <a href="/p/dashboard" class="cev-prof-brand">
        <img src="/img/cev.png" alt="CEV" class="cev-prof-logo">
        <span>CEV Informática</span>
      </a>
      <ul class="cev-prof-nav-links">
        <li><a href="/p/dashboard" class="cev-prof-nav-link">Inicio</a></li>
        <li><a href="/p/materias" class="cev-prof-nav-link">Mis Materias</a></li>
      </ul>
    </div>
    <div class="cev-prof-navbar-end">
      <div class="dropdown" id="notificacionesDropdown">
        <button class="cev-prof-icon-btn position-relative" id="notificationBell" title="Notificaciones" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-bell"></i>
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
      <div class="dropdown">
        <button class="cev-prof-icon-btn dropdown-toggle" data-bs-toggle="dropdown">
          <div class="cev-prof-avatar" id="avatarProfesor">P</div>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
          <li><h6 class="dropdown-header" id="infoProfesor">Profesor</h6></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item" href="/p/perfil"><i class="bi bi-person me-2"></i>Configurar perfil</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item text-danger" href="#" id="btnCerrarSesionProf"><i class="bi bi-box-arrow-right me-2"></i>Cerrar sesi&oacute;n</a></li>
        </ul>
      </div>
    </div>
  </div>
</nav>

<main class="cev-prof-main">
  <?php if (is_string($contentView) && $contentView !== ''): ?>
    <?php require_once BASE_PATH . '/src/views/' . ltrim($contentView, '/'); ?>
  <?php endif; ?>
</main>

<footer class="cev-prof-footer">
  <div class="cev-prof-footer-inner">
    <span>&copy; 2026 CEV Inform&aacute;tica — PNF en Inform&aacute;tica</span>
  </div>
</footer>

<script type="module" src="/js/profesor/profesor.js"></script>
<script type="module" src="/js/notifications.js"></script>
<?php foreach ($pageScripts as $script): ?>
<script src="<?= $script ?>"></script>
<?php endforeach; ?>
<?php foreach ($pageModuleScripts as $script): ?>
<script type="module" src="<?= $script ?>"></script>
<?php endforeach; ?>
</body>
</html>
