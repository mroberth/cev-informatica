<?php
$pageStyles = $pageStyles ?? [];
$pageStyles[] = '/css/modules/student/student.css';
$pageScripts = $pageScripts ?? [];
$pageModuleScripts = $pageModuleScripts ?? [];
$contentView = $contentView ?? null;

require_once BASE_PATH . '/src/views/layouts/head.php';
?>
<body>

<nav class="cev-student-navbar">
  <div class="cev-student-navbar-inner">
    <div class="cev-student-navbar-start">
      <a href="/u/dashboard" class="cev-student-brand">
        <img src="/img/cev.png" alt="CEV" class="cev-student-logo">
        <span>CEV Informática</span>
      </a>
      <ul class="cev-student-nav-links">
        <li><a href="/u/dashboard" class="cev-student-nav-link">Página Principal</a></li>
        <li><a href="/u/area-personal" class="cev-student-nav-link">Área Personal</a></li>
        <li><a href="/u/mis-cursos" class="cev-student-nav-link">Mis Cursos</a></li>
      </ul>
    </div>
    <div class="cev-student-navbar-end">
      <button class="cev-student-icon-btn" title="Notificaciones">
        <i class="bi bi-bell"></i>
      </button>
      <button class="cev-student-icon-btn" title="Chat">
        <i class="bi bi-chat-dots"></i>
      </button>
      <div class="dropdown cev-student-profile-dropdown">
        <button class="cev-student-icon-btn dropdown-toggle" data-bs-toggle="dropdown">
          <div class="cev-student-avatar" id="avatarEstudiante"></div>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
          <li><h6 class="dropdown-header" id="infoEstudiante"></h6></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Configurar perfil</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item text-danger" href="#" id="btnCerrarSesion"><i class="bi bi-box-arrow-right me-2"></i>Cerrar sesión</a></li>
        </ul>
      </div>
    </div>
  </div>
</nav>

<main class="cev-student-main">
  <?php if (is_string($contentView) && $contentView !== ''): ?>
    <?php require_once BASE_PATH . '/src/views/' . ltrim($contentView, '/'); ?>
  <?php endif; ?>
</main>

<footer class="cev-student-footer">
  <div class="cev-student-footer-inner">
    <span>&copy; 2026 CEV Informática — PNF en Informática</span>
  </div>
</footer>

<script type="module" src="/js/student/student.js"></script>
<?php foreach ($pageScripts as $script): ?>
<script src="<?= $script ?>"></script>
<?php endforeach; ?>
<?php foreach ($pageModuleScripts as $script): ?>
<script type="module" src="<?= $script ?>"></script>
<?php endforeach; ?>
</body>
</html>
