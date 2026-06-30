<?php
$pageStyles = ['/plugins/cev-calendar/cev-calendar.css', '/css/modules/dashboard/inicio.css'];
require_once BASE_PATH . '/src/views/layouts/head.php';
?>
<body>

<div class="wrapper">

  <?php require_once BASE_PATH . '/src/views/layouts/sidebar.php'; ?>

  <!-- ===== CONTENIDO PRINCIPAL ===== -->
  <div class="content">

    <?php require_once BASE_PATH . '/src/views/layouts/header.php'; ?>

    <!-- ===== MAIN ===== -->
    <main class="main-content">
      <div class="mb-4">
        <h4 class="mb-1">Bienvenido<span id="textoRolUsuario"></span></h4>
        <p class="text-muted mb-0" id="nombreCompletoUsuario"></p>
      </div>

      <div class="row g-4">
        <div class="col-12">
          <div class="card border-0 shadow-sm rounded-3 dashboard-calendar-card">
            <div class="dashboard-calendar-head">
              <div>
                <h5 class="mb-1">Calendario principal</h5>
                <p class="mb-0">Agenda visual del sistema con eventos integrables por modulo.</p>
              </div>
            </div>
            <div class="card-body p-3 p-md-4">
              <div id="dashboardCalendar" class="dashboard-calendar"></div>
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

<div class="modal fade" id="modalEventoCalendario" tabindex="-1" aria-labelledby="modalEventoCalendarioTitulo" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEventoCalendarioTitulo">Detalle del evento</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body" id="modalEventoCalendarioContenido"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<script src="/plugins/cev-calendar/cev-calendar.js"></script>
<script type="module" src="/js/modules/dashboard/inicio.js"></script>
</body>
</html>
