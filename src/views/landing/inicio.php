<?php require_once BASE_PATH . '/src/views/layouts/head.php'; ?>
<body class="d-flex flex-column min-vh-100 bg-white">

  <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top">
    <div class="container">
      <a class="navbar-brand fw-bold text-primary d-flex align-items-center" href="/">
        <i class="bi bi-mortarboard-fill me-2 fs-4"></i>CEV Informática
      </a>
      <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto align-items-lg-center mt-3 mt-lg-0">
          <li class="nav-item me-lg-3">
            <a class="nav-link active fw-medium" href="#">Inicio</a>
          </li>
          <li class="nav-item me-lg-4">
            <a class="nav-link text-secondary fw-medium" href="#modulos">Módulos</a>
          </li>
          <li class="nav-item">
            <a href="/login" class="btn btn-primary fw-bold px-4 shadow-sm">
              <i class="bi bi-person-fill me-1"></i> Acceder
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <header class="bg-light py-5 border-bottom flex-grow-1 d-flex align-items-center">
    <div class="container py-5">
      <div class="row align-items-center g-5">
        <div class="col-lg-6 text-center text-lg-start">
          <span class="badge bg-primary bg-opacity-10 text-primary fw-bold px-3 py-2 rounded-pill mb-3">PNF en
            Informática</span>
          <h1 class="display-4 fw-bold text-dark mb-3">Control de Estudios <span class="text-primary">Virtual</span>
          </h1>
          <p class="lead text-muted mb-4">Gestiona tus procesos académicos de forma eficiente, rápida y transparente.
            Diseñado exclusivamente para la comunidad universitaria de Informática.</p>
          <div class="d-sm-flex justify-content-center justify-content-lg-start gap-3">
            <a href="/login" class="btn btn-primary btn-lg fw-bold px-4 shadow">Comenzar Ahora</a>
            <a href="#modulos" class="btn btn-outline-secondary btn-lg fw-bold px-4 mt-3 mt-sm-0">Conocer más</a>
          </div>
        </div>
        <div class="col-lg-6 text-center">
          <i class="bi bi-laptop text-primary opacity-25" style="font-size: 12rem;"></i>
        </div>
      </div>
    </div>
  </header>

  <section id="modulos" class="py-5 bg-white">
    <div class="container py-4">
      <div class="text-center mb-5">
        <h2 class="fw-bold text-dark">Servicios del Ecosistema</h2>
        <p class="text-muted">Módulos completamente integrados en una arquitectura desacoplada y escalable.</p>
      </div>
      <div class="row g-4">
        <div class="col-md-4">
          <div class="card h-100 border-0 bg-light p-4 rounded-3 text-center shadow-sm">
            <div class="text-primary mb-3"><i class="bi bi-file-earmark-text fs-1"></i></div>
            <h4 class="fw-bold fs-5">Control de Calificaciones</h4>
            <p class="text-muted small mb-0">Planes de evaluación dinámicos y asentamiento de notas bajo la escala
              oficial de 1 a 20 puntos por cada unidad curricular.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card h-100 border-0 bg-light p-4 rounded-3 text-center shadow-sm">
            <div class="text-primary mb-3"><i class="bi bi-diagram-3 fs-1"></i></div>
            <h4 class="fw-bold fs-5">Eje de Proyectos (PST)</h4>
            <p class="text-muted small mb-0">Seguimiento riguroso del Proyecto Socio-Tecnológico a lo largo de los
              Trayectos, pilar fundamental del PNF.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card h-100 border-0 bg-light p-4 rounded-3 text-center shadow-sm">
            <div class="text-primary mb-3"><i class="bi bi-journal-bookmark-fill fs-1"></i></div>
            <h4 class="fw-bold fs-5">Planificación Académica</h4>
            <p class="text-muted small mb-0">Gestión estructurada de trayectos, ofertas de unidades curriculares y
              asignación de carga docente por secciones.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card h-100 border-0 bg-light p-4 rounded-3 text-center shadow-sm">
            <div class="text-primary mb-3"><i class="bi bi-person-badge fs-1"></i></div>
            <h4 class="fw-bold fs-5">Historial e Inscripciones</h4>
            <p class="text-muted small mb-0">Control del estado académico de los estudiantes (Activo, Egresado,
              Retirado) vinculados directamente a sus secciones.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card h-100 border-0 bg-light p-4 rounded-3 text-center shadow-sm">
            <div class="text-primary mb-3"><i class="bi bi-shield-check fs-1"></i></div>
            <h4 class="fw-bold fs-5">Seguridad & RBAC Granular</h4>
            <p class="text-muted small mb-0">Autenticación mediante JWT con control de acceso basado en roles, módulos y
              permisos específicos (Crear, Leer, Editar, Eliminar).</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card h-100 border-0 bg-light p-4 rounded-3 text-center shadow-sm">
            <div class="text-primary mb-3"><i class="bi bi-clock-history fs-1"></i></div>
            <h4 class="fw-bold fs-5">Auditoría con Bitácora</h4>
            <p class="text-muted small mb-0">Registro inmutable de operaciones críticas del sistema, trazabilidad total
              e implementación estricta de Rate Limit.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="trayectos" class="py-5 bg-light border-top border-bottom">
    <div class="container py-4">
      <div class="text-center mb-5">
        <span class="badge bg-primary bg-opacity-10 text-primary fw-bold px-3 py-2 rounded-pill mb-2">Estructura
          Académica</span>
        <h2 class="fw-bold text-dark">Organización por Trayectos</h2>
        <p class="text-muted">El sistema adapta su flujo transaccional según el avance del estudiante en el PNF.</p>
      </div>
      <div class="row g-4 justify-content-center">
        <div class="col-lg-3 col-md-6">
          <div class="p-4 bg-white rounded-3 shadow-sm border-start border-primary border-4 h-100">
            <h5 class="fw-bold text-primary mb-2">Trayecto Inicial</h5>
            <p class="text-muted small mb-0">Fase de inducción universitaria y nivelación en matemáticas, lógica y
              herramientas de oficina.</p>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="p-4 bg-white rounded-3 shadow-sm border-start border-primary border-4 h-100">
            <h5 class="fw-bold text-primary mb-2">Trayectos I y II</h5>
            <p class="text-muted small mb-0">Desarrollo de competencias clave: Algorítmica, Bases de Datos, Ingeniería
              de Software y Redes.</p>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="p-4 bg-white rounded-3 shadow-sm border-start border-primary border-4 h-100">
            <h5 class="fw-bold text-primary mb-2">Trayectos III y IV</h5>
            <p class="text-muted small mb-0">Arquitectura de software avanzada, seguridad informática, auditoría de
              sistemas y gestión de proyectos complejos.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="roles" class="py-5 bg-white">
    <div class="container py-4">
      <div class="text-center mb-5">
        <h2 class="fw-bold text-dark">Un Entorno para Cada Actor</h2>
        <p class="text-muted">La matriz de permisos restringe las acciones garantizando la integridad de los datos
          académicos.</p>
      </div>
      <div class="row g-4">
        <div class="col-lg-4">
          <div class="p-4 border rounded-3 h-100 shadow-sm">
            <div class="d-flex align-items-center mb-3">
              <div class="bg-primary text-white p-2 rounded-2 me-3"><i class="bi bi-person-workspace fs-4"></i></div>
              <h4 class="fw-bold m-0 fs-5">Personal Docente</h4>
            </div>
            <p class="text-muted small mb-0">Configuración autónoma de sus planes de evaluación por unidad curricular,
              carga rápida de notas, asistencia y visualización de sus secciones asignadas en el período activo.</p>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="p-4 border rounded-3 h-100 shadow-sm">
            <div class="d-flex align-items-center mb-3">
              <div class="bg-success text-white p-2 rounded-2 me-3"><i class="bi bi-mortarboard fs-4"></i></div>
              <h4 class="fw-bold m-0 fs-5">Estudiantes PNF</h4>
            </div>
            <p class="text-muted small mb-0">Consulta inmediata de calificaciones parciales y definitivas por trayecto,
              estatus de inscripción, control de unidades de crédito acumuladas e historial académico.</p>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="p-4 border rounded-3 h-100 shadow-sm">
            <div class="d-flex align-items-center mb-3">
              <div class="bg-dark text-white p-2 rounded-2 me-3"><i class="bi bi-sliders fs-4"></i></div>
              <h4 class="fw-bold m-0 fs-5">Control de Estudios</h4>
            </div>
            <p class="text-muted small mb-0">Apertura de periodos académicos, administración de la malla curricular,
              inscripción masiva o selectiva, auditoría completa mediante bitácora y asignación de roles del sistema.
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <?php require_once BASE_PATH . '/src/views/layouts/footer.php'; ?>
</body>
</html>