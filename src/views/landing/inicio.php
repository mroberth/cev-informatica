<?php require_once BASE_PATH . '/src/views/layouts/head.php'; ?>

<style>
  :root {
    --cev-primary-gradient: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    --cev-dark-gradient: linear-gradient(135deg, #121824 0%, #1a2333 100%);
    --cev-cyber-gradient: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
    --cev-card-bg: #ffffff;
    --cev-transition-smooth: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
  }

  /* Smooth scroll support */
  html {
    scroll-behavior: smooth;
  }

  /* Custom typography gradients */
  .text-gradient {
    background: var(--cev-cyber-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    display: inline-block;
  }

  /* Beautiful card animations */
  .card-hover {
    transition: var(--cev-transition-smooth);
    border: 1px solid rgba(0, 0, 0, 0.05) !important;
  }

  .card-hover:hover {
    transform: translateY(-8px);
    box-shadow: 0 1.5rem 3rem rgba(13, 110, 253, 0.1) !important;
    border-color: rgba(13, 110, 253, 0.2) !important;
  }

  /* Button hover scaling */
  .btn-hover {
    transition: var(--cev-transition-smooth);
  }

  .btn-hover:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1.5rem rgba(13, 110, 253, 0.25) !important;
  }

  /* Interactive tech badges */
  .tech-badge {
    transition: var(--cev-transition-smooth);
    cursor: default;
    background-color: #f8f9fa;
    border: 1px solid rgba(0, 0, 0, 0.06);
    color: #495057;
    font-weight: 500;
  }

  .tech-badge:hover {
    background-color: rgba(13, 110, 253, 0.08) !important;
    border-color: #0d6efd !important;
    color: #0d6efd !important;
    transform: scale(1.05);
  }

  /* Timeline design */
  .timeline-steps {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    position: relative;
  }

  .timeline-step {
    position: relative;
    text-align: center;
    flex: 1;
    min-width: 250px;
    z-index: 2;
  }

  @media (min-width: 992px) {
    .timeline-steps::before {
      content: '';
      position: absolute;
      top: 2.5rem;
      left: 10%;
      right: 10%;
      height: 4px;
      background: linear-gradient(to right, #0d6efd 20%, #0dcaf0 80%);
      z-index: 1;
      border-radius: 2px;
      opacity: 0.3;
    }
  }

  .step-icon {
    width: 5rem;
    height: 5rem;
    line-height: 5rem;
    border-radius: 50%;
    background-color: #ffffff;
    border: 3px solid #0d6efd;
    color: #0d6efd;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    font-weight: bold;
    margin-bottom: 1.25rem;
    position: relative;
    z-index: 3;
    box-shadow: 0 0.5rem 1.25rem rgba(0, 0, 0, 0.05);
    transition: var(--cev-transition-smooth);
  }

  .timeline-step:hover .step-icon {
    background: var(--cev-cyber-gradient);
    border-color: transparent;
    color: #ffffff;
    box-shadow: 0 1rem 2rem rgba(13, 110, 253, 0.3);
    transform: rotate(360deg) scale(1.1);
  }

  /* Live activity indicator pulse */
  .pulse-indicator {
    width: 10px;
    height: 10px;
    background-color: #198754;
    border-radius: 50%;
    display: inline-block;
    box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.7);
    animation: pulse 1.6s infinite;
  }

  @keyframes pulse {
    0% {
      transform: scale(0.95);
      box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.7);
    }
    70% {
      transform: scale(1);
      box-shadow: 0 0 0 8px rgba(25, 135, 84, 0);
    }
    100% {
      transform: scale(0.95);
      box-shadow: 0 0 0 0 rgba(25, 135, 84, 0);
    }
  }

  /* Simulated preview dashboard card */
  .dashboard-preview {
    background: #121824;
    border-radius: 1rem;
    box-shadow: 0 2rem 4rem rgba(0,0,0,0.15);
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.08);
  }

  .dashboard-header {
    background: #1a2333;
    padding: 0.75rem 1.25rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    display: flex;
    align-items: center;
  }

  .window-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-right: 6px;
    display: inline-block;
  }

  .window-dot-red { background: #ff5f56; }
  .window-dot-yellow { background: #ffbd2e; }
  .window-dot-green { background: #27c93f; }

  /* Smooth collapse transitions for FAQ */
  .accordion-button:not(.collapsed) {
    background-color: rgba(13, 110, 253, 0.05);
    color: #0d6efd;
    box-shadow: none;
  }

  .accordion-button:focus {
    box-shadow: none;
    border-color: rgba(13, 110, 253, 0.25);
  }
</style>

<body class="d-flex flex-column min-vh-100 bg-white">

  <!-- NAVBAR OPTIMIZADA -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top shadow-sm py-3">
    <div class="container">
      <a class="navbar-brand fw-bold text-primary d-flex align-items-center fs-4" href="/">
        <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2 me-2 d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
          <i class="bi bi-mortarboard-fill fs-5"></i>
        </div>
        CEV<span class="text-dark fw-light">Informática</span>
      </a>
      <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto align-items-lg-center">
          <li class="nav-item me-lg-2">
            <a class="nav-link active fw-semibold text-primary px-3" href="#">Inicio</a>
          </li>
          <li class="nav-item me-lg-2">
            <a class="nav-link text-secondary fw-semibold px-3 hover-text-primary" href="#modulos">Ecosistema</a>
          </li>
          <li class="nav-item me-lg-2">
            <a class="nav-link text-secondary fw-semibold px-3 hover-text-primary" href="#trayectos">Ruta Académica</a>
          </li>
          <li class="nav-item me-lg-3">
            <a class="nav-link text-secondary fw-semibold px-3 hover-text-primary" href="#faq">Ayuda</a>
          </li>
          <li class="nav-item mt-3 mt-lg-0">
            <a href="/login" class="btn btn-primary fw-bold px-4 py-2 rounded-3 shadow-sm btn-hover d-flex align-items-center justify-content-center">
              <i class="bi bi-person-fill me-1"></i> Acceder al Portal
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- HERO SECTION CON ENFOQUE CRO (PAS) -->
  <header class="bg-gradient-hero py-5 border-bottom flex-grow-1 d-flex align-items-center position-relative overflow-hidden">
    <div class="container py-5">
      <div class="row align-items-center g-5">
        <!-- Text Column -->
        <div class="col-lg-6 text-center text-lg-start">
          <span class="badge bg-primary bg-opacity-10 text-primary fw-semibold px-3 py-2 rounded-pill mb-3 d-inline-flex align-items-center">
            <span class="pulse-indicator me-2"></span> Portal de Control de Estudios Virtual
          </span>
          <h1 class="display-4 fw-extrabold text-dark mb-3 lh-sm" style="letter-spacing: -1px;">
            Estudia Informática con las <span class="text-gradient fw-bold">Herramientas del Mañana</span>
          </h1>
          <p class="lead text-muted mb-4 fs-5" style="line-height: 1.7;">
            ¿Cansado de la burocracia, notas perdidas y colas interminables? 
            Simplifica tu vida académica con el <strong>CEV</strong>: la plataforma ultra veloz, segura y transparente, co-diseñada para la nueva generación de ingenieros y desarrolladores de software.
          </p>
          <div class="d-flex flex-column flex-sm-row justify-content-center justify-content-lg-start gap-3">
            <a href="/login" class="btn btn-primary btn-lg fw-bold px-4 py-3 shadow-sm btn-hover d-flex align-items-center justify-content-center">
              <i class="bi bi-rocket-takeoff-fill me-2"></i> Comenzar Ahora
            </a>
            <a href="#modulos" class="btn btn-outline-secondary btn-lg fw-bold px-4 py-3 btn-hover d-flex align-items-center justify-content-center">
              Explorar Ecosistema <i class="bi bi-arrow-down-short ms-1 fs-5"></i>
            </a>
          </div>
        </div>
        
        <!-- Interactive Code/Dashboard Preview Column (UI/UX Trust Builder) -->
        <div class="col-lg-6">
          <div class="dashboard-preview">
            <div class="dashboard-header">
              <span class="window-dot window-dot-red"></span>
              <span class="window-dot window-dot-yellow"></span>
              <span class="window-dot window-dot-green"></span>
              <span class="text-muted ms-3 small font-monospace">cev-informatica-core-v2.0</span>
            </div>
            <div class="p-4 font-monospace text-start" style="font-size: 0.85rem; color: #a9b2c3;">
              <div class="mb-3 text-success">// Inicializando sistema de control de estudios virtual...</div>
              <div class="mb-2"><span class="text-primary">const</span> student = {</div>
              <div class="mb-2" style="padding-left: 1.5rem;">name: <span class="text-warning">"Estudiante PNF Informática"</span>,</div>
              <div class="mb-2" style="padding-left: 1.5rem;">averageGrade: <span class="text-info">18.5</span>,</div>
              <div class="mb-2" style="padding-left: 1.5rem;">status: <span class="text-success">"Activo"</span>,</div>
              <div class="mb-2" style="padding-left: 1.5rem;">project_PST: <span class="text-warning">"Aprobado - Ciclo de Vida Web"</span>,</div>
              <div class="mb-3" style="padding-left: 1.5rem;">securityToken: <span class="text-info">"JWT_ACTIVE_SESSION_SHA256"</span></div>
              <div class="mb-3">};</div>
              
              <!-- Simulated Interactive Widget inside Code Editor -->
              <div class="bg-white bg-opacity-5 p-3 rounded border border-secondary border-opacity-20 mb-2">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span class="text-white fw-bold" style="font-family: sans-serif;"><i class="bi bi-shield-check text-info me-1"></i> Estado del Servidor</span>
                  <span class="badge bg-success bg-opacity-25 text-success font-sans border border-success border-opacity-20" style="font-family: sans-serif;">En Línea</span>
                </div>
                <div class="progress bg-secondary bg-opacity-20" style="height: 6px;">
                  <div class="progress-bar bg-info" style="width: 100%;"></div>
                </div>
                <div class="d-flex justify-content-between text-muted small mt-2" style="font-family: sans-serif; font-size: 0.75rem;">
                  <span>Latencia: 18ms</span>
                  <span>Seguridad: RBAC Activa</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>

  <!-- SECCIÓN MÓDULOS DEL ECOSISTEMA (CRO BENEFIT-ORIENTED) -->
  <section id="modulos" class="py-5 bg-white">
    <div class="container py-5">
      <div class="text-center max-w-2xl mx-auto mb-5">
        <span class="badge bg-primary bg-opacity-10 text-primary fw-bold px-3 py-2 rounded-pill mb-2">ARQUITECTURA DE VANGUARDIA</span>
        <h2 class="fw-bold text-dark display-6">Módulos Completos, Flujo Sin Fricción</h2>
        <p class="text-muted fs-5">Olvídate de la lentitud burocrática. Tu control de estudios corre bajo un motor diseñado para la máxima velocidad y transparencia.</p>
      </div>
      
      <div class="row g-4">
        <!-- Card 1 -->
        <div class="col-md-4">
          <div class="card h-100 card-hover bg-light p-4 rounded-4 shadow-sm">
            <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-3 mb-4" style="width: 50px; height: 50px;">
              <i class="bi bi-file-earmark-text fs-3"></i>
            </div>
            <h4 class="fw-bold fs-5 text-dark mb-3">Calificaciones en Tiempo Real</h4>
            <p class="text-muted small mb-0">Cero sorpresas. Consulta tus planes de evaluación y el asentamiento de notas de 1 a 20 de forma inmediata desde cualquier dispositivo móvil.</p>
          </div>
        </div>
        
        <!-- Card 2 -->
        <div class="col-md-4">
          <div class="card h-100 card-hover bg-light p-4 rounded-4 shadow-sm">
            <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-3 mb-4" style="width: 50px; height: 50px;">
              <i class="bi bi-diagram-3 fs-3"></i>
            </div>
            <h4 class="fw-bold fs-5 text-dark mb-3">Aceleradora de Proyectos (PST)</h4>
            <p class="text-muted small mb-0">La espina dorsal del PNF sin el laberinto de papeleos. Monitorea el ciclo de vida de tu Proyecto Socio-Tecnológico por trayectos y recibe aprobación ágil de tutores.</p>
          </div>
        </div>
        
        <!-- Card 3 -->
        <div class="col-md-4">
          <div class="card h-100 card-hover bg-light p-4 rounded-4 shadow-sm">
            <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-3 mb-4" style="width: 50px; height: 50px;">
              <i class="bi bi-journal-bookmark-fill fs-3"></i>
            </div>
            <h4 class="fw-bold fs-5 text-dark mb-3">Planificación Inteligente</h4>
            <p class="text-muted small mb-0">Configura trayectos, asigna cargas docentes por secciones y optimiza la oferta académica para evitar solapamientos de horarios de manera automática.</p>
          </div>
        </div>
        
        <!-- Card 4 -->
        <div class="col-md-4">
          <div class="card h-100 card-hover bg-light p-4 rounded-4 shadow-sm">
            <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-3 mb-4" style="width: 50px; height: 50px;">
              <i class="bi bi-person-badge fs-3"></i>
            </div>
            <h4 class="fw-bold fs-5 text-dark mb-3">Historial & Expediente Ágil</h4>
            <p class="text-muted small mb-0">Control impecable de tu estatus académico (Activo, Egresado, Retirado). Accede a tu histórico acumulado y secciones vigentes en un clic.</p>
          </div>
        </div>
        
        <!-- Card 5 -->
        <div class="col-md-4">
          <div class="card h-100 card-hover bg-light p-4 rounded-4 shadow-sm">
            <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-3 mb-4" style="width: 50px; height: 50px;">
              <i class="bi bi-shield-check fs-3"></i>
            </div>
            <h4 class="fw-bold fs-5 text-dark mb-3">Seguridad y RBAC Granular</h4>
            <p class="text-muted small mb-0">Tus datos están blindados. El sistema utiliza tokens JWT robustos con control de acceso estricto basado en roles y permisos modulares (CRUD).</p>
          </div>
        </div>
        
        <!-- Card 6 -->
        <div class="col-md-4">
          <div class="card h-100 card-hover bg-light p-4 rounded-4 shadow-sm">
            <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-3 mb-4" style="width: 50px; height: 50px;">
              <i class="bi bi-clock-history fs-3"></i>
            </div>
            <h4 class="fw-bold fs-5 text-dark mb-3">Auditoría con Bitácora Digital</h4>
            <p class="text-muted small mb-0">Transparencia absoluta y trazabilidad inmutable de todas las operaciones del sistema, respaldado con defensas activas de Rate Limit.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- SECCIÓN RUTA ACADÉMICA / TRAYECTOS + TECNOLOGÍAS REALES -->
  <section id="trayectos" class="py-5 bg-light border-top border-bottom">
    <div class="container py-5">
      <div class="text-center mb-5">
        <span class="badge bg-primary bg-opacity-10 text-primary fw-bold px-3 py-2 rounded-pill mb-2">MAPA DE RUTA TECNOLÓGICA</span>
        <h2 class="fw-bold text-dark display-6">De Estudiante a Ingeniero de Software</h2>
        <p class="text-muted fs-5">Evoluciona tu perfil técnico a lo largo de los trayectos académicos integrados en el sistema.</p>
      </div>
      
      <div class="timeline-steps">
        <!-- Step 1 -->
        <div class="timeline-step mb-5 mb-lg-0">
          <div class="step-icon"><i class="bi bi-code-slash"></i></div>
          <h4 class="fw-bold text-dark mb-2">Trayecto Inicial</h4>
          <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill mb-3">Fundamentos</span>
          <p class="text-muted small px-3">Fase de nivelación y asimilación universitaria. Domina la lógica de programación, algoritmia básica y herramientas administrativas.</p>
          <div class="d-flex flex-wrap justify-content-center gap-1 mt-3 px-3">
            <span class="badge tech-badge rounded-pill">Lógica de Código</span>
            <span class="badge tech-badge rounded-pill">Algoritmos</span>
            <span class="badge tech-badge rounded-pill">Markdown</span>
          </div>
        </div>
        
        <!-- Step 2 -->
        <div class="timeline-step mb-5 mb-lg-0">
          <div class="step-icon"><i class="bi bi-database"></i></div>
          <h4 class="fw-bold text-dark mb-2">Trayectos I y II</h4>
          <span class="badge bg-info bg-opacity-10 text-info rounded-pill mb-3">Construcción Core</span>
          <p class="text-muted small px-3">Construye software robusto. Diseña bases de datos relacionales avanzadas, domina el paradigma orientado a objetos e infraestructura de redes.</p>
          <div class="d-flex flex-wrap justify-content-center gap-1 mt-3 px-3">
            <span class="badge tech-badge rounded-pill">Python</span>
            <span class="badge tech-badge rounded-pill">JavaScript</span>
            <span class="badge tech-badge rounded-pill">MySQL/SQL</span>
            <span class="badge tech-badge rounded-pill">PHP</span>
            <span class="badge tech-badge rounded-pill">Linux</span>
            <span class="badge tech-badge rounded-pill">Git</span>
          </div>
        </div>
        
        <!-- Step 3 -->
        <div class="timeline-step">
          <div class="step-icon"><i class="bi bi-cpu"></i></div>
          <h4 class="fw-bold text-dark mb-2">Trayectos III y IV</h4>
          <span class="badge bg-success bg-opacity-10 text-success rounded-pill mb-3">Alta Ingeniería</span>
          <p class="text-muted small px-3">Domina la arquitectura empresarial, audita sistemas críticos, despliega contenedores y gestiona proyectos tecnológicos de alta complejidad.</p>
          <div class="d-flex flex-wrap justify-content-center gap-1 mt-3 px-3">
            <span class="badge tech-badge rounded-pill">React</span>
            <span class="badge tech-badge rounded-pill">Laravel</span>
            <span class="badge tech-badge rounded-pill">Docker</span>
            <span class="badge tech-badge rounded-pill">Ciberseguridad</span>
            <span class="badge tech-badge rounded-pill">Auditoría IT</span>
            <span class="badge tech-badge rounded-pill">Patrones MVC</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- SECCIÓN DE ACTORES CON FOCO EN VALOR (ROLES) -->
  <section id="roles" class="py-5 bg-white">
    <div class="container py-5">
      <div class="text-center mb-5">
        <span class="badge bg-primary bg-opacity-10 text-primary fw-bold px-3 py-2 rounded-pill mb-2">ESPACIOS DE TRABAJO</span>
        <h2 class="fw-bold text-dark display-6">Diseñado para cada Actor del Ecosistema</h2>
        <p class="text-muted fs-5">Cada perfil dispone de un dashboard inteligente y un juego de herramientas personalizado.</p>
      </div>
      
      <div class="row g-4">
        <!-- Actor 1 -->
        <div class="col-lg-4">
          <div class="p-5 border rounded-4 h-100 shadow-sm bg-white card-hover border-start border-primary border-4">
            <div class="d-flex align-items-center mb-4">
              <div class="bg-primary text-white p-3 rounded-3 me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                <i class="bi bi-mortarboard fs-4"></i>
              </div>
              <h4 class="fw-bold m-0 fs-5 text-dark">Estudiantes PNF</h4>
            </div>
            <p class="text-muted mb-4 small" style="line-height: 1.6;">Toma el timón de tu carrera académica. Consulta notas al instante, vigila el avance del Proyecto (PST) en tu trayecto, revisa créditos acumulados y descarga tu historial sin pedir una sola cita.</p>
            <div class="border-top pt-3">
              <span class="text-primary small fw-bold"><i class="bi bi-check-circle-fill me-1"></i> Estado académico transparente</span>
            </div>
          </div>
        </div>
        
        <!-- Actor 2 -->
        <div class="col-lg-4">
          <div class="p-5 border rounded-4 h-100 shadow-sm bg-white card-hover border-start border-success border-4">
            <div class="d-flex align-items-center mb-4">
              <div class="bg-success text-white p-3 rounded-3 me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                <i class="bi bi-person-workspace fs-4"></i>
              </div>
              <h4 class="fw-bold m-0 fs-5 text-dark">Personal Docente</h4>
            </div>
            <p class="text-muted mb-4 small" style="line-height: 1.6;">Optimiza tu tiempo. Define tus actas y planes de evaluación en segundos, asienta calificaciones por secciones de manera masiva y visualiza tus asignaciones del trayecto sin lidiar con papeleos complejos.</p>
            <div class="border-top pt-3">
              <span class="text-success small fw-bold"><i class="bi bi-check-circle-fill me-1"></i> Carga masiva en segundos</span>
            </div>
          </div>
        </div>
        
        <!-- Actor 3 -->
        <div class="col-lg-4">
          <div class="p-5 border rounded-4 h-100 shadow-sm bg-white card-hover border-start border-dark border-4">
            <div class="d-flex align-items-center mb-4">
              <div class="bg-dark text-white p-3 rounded-3 me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                <i class="bi bi-sliders fs-4"></i>
              </div>
              <h4 class="fw-bold m-0 fs-5 text-dark">Control de Estudios</h4>
            </div>
            <p class="text-muted mb-4 small" style="line-height: 1.6;">El núcleo de administración global. Abre períodos académicos, aprueba inscripciones por lotes, asigna carga docente por secciones, audita transacciones mediante bitácora e implementa seguridad RBAC total.</p>
            <div class="border-top pt-3">
              <span class="text-dark small fw-bold"><i class="bi bi-check-circle-fill me-1"></i> Trazabilidad del sistema al 100%</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- SECCIÓN NUEVA: SOCIAL PROOF / TESTIMONIOS (CONFIANZA REAL) -->
  <section id="testimonios" class="py-5 bg-light border-top border-bottom">
    <div class="container py-5">
      <div class="text-center mb-5">
        <span class="badge bg-primary bg-opacity-10 text-primary fw-bold px-3 py-2 rounded-pill mb-2">TESTIMONIOS REALES</span>
        <h2 class="fw-bold text-dark display-6">Respaldado por la Comunidad</h2>
        <p class="text-muted fs-5">Estudiantes y docentes confirman el impacto directo de la plataforma en su rendimiento diario.</p>
      </div>
      
      <div class="row g-4">
        <!-- Testimonio 1 -->
        <div class="col-md-4">
          <div class="p-4 bg-white rounded-4 shadow-sm h-100 d-flex flex-column justify-content-between border border-light">
            <div>
              <div class="text-warning mb-3">
                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
              </div>
              <p class="text-muted small font-italic mb-4" style="line-height: 1.6;">
                "Registrar el PST solía requerir semanas de firmas y carpetas físicas que se traspapelaban. Con el CEV, cargamos el avance del proyecto, asignamos los trayectos y nuestro tutor lo aprobó en minutos. Es una herramienta digna de una carrera de informática."
              </p>
            </div>
            <div class="d-flex align-items-center">
              <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold" style="width: 45px; height: 45px;">DR</div>
              <div>
                <h6 class="fw-bold mb-0 text-dark">Daniel Rivas</h6>
                <small class="text-muted">Estudiante del Trayecto IV</small>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Testimonio 2 -->
        <div class="col-md-4">
          <div class="p-4 bg-white rounded-4 shadow-sm h-100 d-flex flex-column justify-content-between border border-light">
            <div>
              <div class="text-warning mb-3">
                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
              </div>
              <p class="text-muted small font-italic mb-4" style="line-height: 1.6;">
                "La carga de calificaciones ahora es un proceso de segundos. La configuración de unidades de evaluación es dinámica y no dependo de plantillas manuales obsoletas. Además, el control de accesos por roles previene cualquier error humano."
              </p>
            </div>
            <div class="d-flex align-items-center">
              <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold" style="width: 45px; height: 45px;">EM</div>
              <div>
                <h6 class="fw-bold mb-0 text-dark">Dra. Elena Martínez</h6>
                <small class="text-muted">Docente de Ingeniería de Software</small>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Testimonio 3 -->
        <div class="col-md-4">
          <div class="p-4 bg-white rounded-4 shadow-sm h-100 d-flex flex-column justify-content-between border border-light">
            <div>
              <div class="text-warning mb-3">
                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
              </div>
              <p class="text-muted small font-italic mb-4" style="line-height: 1.6;">
                "Para nosotros en administración de control de estudios, la bitácora inmutable de auditoría y la autenticación JWT con Rate Limit son cruciales. Nos garantizan protección contra accesos maliciosos y transparencia completa sobre los datos."
              </p>
            </div>
            <div class="d-flex align-items-center">
              <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold" style="width: 45px; height: 45px;">CM</div>
              <div>
                <h6 class="fw-bold mb-0 text-dark">Ing. Carlos Mendoza</h6>
                <small class="text-muted">Coordinador de Control de Estudios</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- SECCIÓN NUEVA: PREGUNTAS FRECUENTES (FAQ - DERRIBANDO FRICCIÓN) -->
  <section id="faq" class="py-5 bg-white">
    <div class="container py-5">
      <div class="text-center mb-5">
        <span class="badge bg-primary bg-opacity-10 text-primary fw-bold px-3 py-2 rounded-pill mb-2">SOPORTE Y AYUDA</span>
        <h2 class="fw-bold text-dark display-6">Preguntas Frecuentes</h2>
        <p class="text-muted fs-5">Resolvemos tus dudas técnicas y académicas sobre el funcionamiento de la plataforma.</p>
      </div>
      
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="accordion accordion-flush" id="accordionFaq">
            <!-- FAQ 1 -->
            <div class="accordion-item border-bottom py-3">
              <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button collapsed fw-bold fs-6 text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                  ¿Cómo garantiza el CEV la seguridad de mis notas y datos?
                </button>
              </h2>
              <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionFaq">
                <div class="accordion-body text-muted small">
                  El sistema implementa una arquitectura segura de autenticación mediante tokens robustos JWT que expiran automáticamente. Todo el acceso es administrado mediante una estricta jerarquía de roles (RBAC) y cada cambio se graba de manera inalterable en una bitácora de auditoría que incluye IP y tipo de navegador para mayor trazabilidad.
                </div>
              </div>
            </div>
            
            <!-- FAQ 2 -->
            <div class="accordion-item border-bottom py-3">
              <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed fw-bold fs-6 text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                  ¿Es compatible con mi teléfono celular o tablet?
                </button>
              </h2>
              <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionFaq">
                <div class="accordion-body text-muted small">
                  Totalmente. La interfaz ha sido diseñada utilizando Bootstrap 5 bajo la filosofía Mobile-First. Se adapta fluidamente a pantallas de teléfonos inteligentes, tabletas y computadoras de escritorio, garantizando que puedas consultar tus notas y registrar proyectos desde donde sea.
                </div>
              </div>
            </div>
            
            <!-- FAQ 3 -->
            <div class="accordion-item border-bottom py-3">
              <h2 class="accordion-header" id="headingThree">
                <button class="accordion-button collapsed fw-bold fs-6 text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                  ¿Cómo funciona el seguimiento del Proyecto Socio-Tecnológico (PST)?
                </button>
              </h2>
              <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionFaq">
                <div class="accordion-body text-muted small">
                  El PST es clasificado de acuerdo con tu Trayecto activo. El sistema de bases de datos vincula dinámicamente a tu equipo con un tutor calificado. Puedes cargar tus hitos y estatus de desarrollo, reduciendo significativamente las reuniones de firmas tradicionales y acelerando los procesos de aprobación académica.
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- LLAMADA A LA ACCIÓN FINAL (CTA CRO) -->
  <section class="py-5" style="background: var(--cev-dark-gradient);">
    <div class="container py-5 text-center text-white">
      <h2 class="display-6 fw-bold mb-3">¿Listo para programar tu éxito académico?</h2>
      <p class="text-muted fs-5 mb-4 max-w-lg mx-auto" style="color: #a9b2c3 !important;">Ingresa hoy mismo a la plataforma que merecen los profesionales de la informática.</p>
      <a href="/login" class="btn btn-primary btn-lg fw-bold px-5 py-3 shadow-sm btn-hover rounded-3">
        Acceder al Ecosistema <i class="bi bi-arrow-right-short ms-1 fs-4"></i>
      </a>
    </div>
  </section>

  <?php require_once BASE_PATH . '/src/views/layouts/footer.php'; ?>
</body>
</html>
