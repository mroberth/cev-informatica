<main class="main-content">
  <div class="mb-4 d-flex flex-wrap align-items-center justify-content-between gap-2">
    <div>
      <h4 class="mb-1"><i class="bi bi-graph-up me-2"></i>Reportes Estadísticos</h4>
      <p class="text-muted mb-0">Panorama general del rendimiento académico y la gestión institucional</p>
    </div>
  </div>

  <div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body p-3 p-md-4">
      <div class="row g-3 align-items-end">
        <div class="col-md-3">
          <label for="filtroPeriodo" class="form-label fw-semibold text-secondary small">Período Académico</label>
          <select id="filtroPeriodo" class="form-select form-select-sm">
            <option value="">Todos los períodos</option>
          </select>
        </div>
        <div class="col-md-9 d-flex gap-2 align-items-end">
          <button id="btnActualizar" class="btn btn-primary btn-sm" style="background:#1a2a4a;">
            <i class="bi bi-arrow-clockwise me-1"></i>Actualizar
          </button>
          <div class="small text-muted ms-auto" id="ultimaActualizacion"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- KPI cards row -->
  <div class="row g-4 mb-4" id="kpiRow">
    <div class="col-md-3 col-6">
      <div class="card border-0 shadow-sm rounded-3 text-center h-100">
        <div class="card-body">
          <div class="text-muted small text-uppercase fw-semibold mb-2">Estudiantes</div>
          <div class="display-5 fw-bold" style="color:#1a2a4a;" id="kpiEstudiantes">—</div>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-6">
      <div class="card border-0 shadow-sm rounded-3 text-center h-100">
        <div class="card-body">
          <div class="text-muted small text-uppercase fw-semibold mb-2">Promedio General</div>
          <div class="display-5 fw-bold" style="color:#1a2a4a;" id="kpiPromedio">—</div>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-6">
      <div class="card border-0 shadow-sm rounded-3 text-center h-100">
        <div class="card-body">
          <div class="text-muted small text-uppercase fw-semibold mb-2">Aprobación</div>
          <div class="display-5 fw-bold" style="color:#198754;" id="kpiAprobacion">—</div>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-6">
      <div class="card border-0 shadow-sm rounded-3 text-center h-100">
        <div class="card-body">
          <div class="text-muted small text-uppercase fw-semibold mb-2">Docentes</div>
          <div class="display-5 fw-bold" style="color:#1a2a4a;" id="kpiDocentes">—</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Charts row 1 -->
  <div class="row g-4 mb-4">
    <div class="col-md-6">
      <div class="card border-0 shadow-sm rounded-3 h-100">
        <div class="card-body">
          <h6 class="fw-bold mb-3"><i class="bi bi-people me-2"></i>Estudiantes por Trayecto</h6>
          <div class="chart-wrapper"><canvas id="chartEstudiantesTrayecto"></canvas></div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card border-0 shadow-sm rounded-3 h-100">
        <div class="card-body">
          <h6 class="fw-bold mb-3"><i class="bi bi-bar-chart me-2"></i>Distribución de Notas</h6>
          <div class="chart-wrapper"><canvas id="chartDistribucionNotas"></canvas></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Charts row 2 -->
  <div class="row g-4 mb-4">
    <div class="col-md-6">
      <div class="card border-0 shadow-sm rounded-3 h-100">
        <div class="card-body">
          <h6 class="fw-bold mb-3"><i class="bi bi-journal-text me-2"></i>Rendimiento por Materia</h6>
          <div class="chart-wrapper"><canvas id="chartRendimientoMateria"></canvas></div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card border-0 shadow-sm rounded-3 h-100">
        <div class="card-body">
          <h6 class="fw-bold mb-3"><i class="bi bi-graph-up-arrow me-2"></i>Tendencia de Inscripciones</h6>
          <div class="chart-wrapper"><canvas id="chartTendencia"></canvas></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Charts row 3 -->
  <div class="row g-4">
    <div class="col-md-6">
      <div class="card border-0 shadow-sm rounded-3 h-100">
        <div class="card-body">
          <h6 class="fw-bold mb-3"><i class="bi bi-person-badge me-2"></i>Top Docentes por Materias Asignadas</h6>
          <div class="chart-wrapper"><canvas id="chartTopDocentes"></canvas></div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card border-0 shadow-sm rounded-3 h-100">
        <div class="card-body">
          <h6 class="fw-bold mb-3"><i class="bi bi-pie-chart me-2"></i>Estado de Estudiantes</h6>
          <div class="chart-wrapper"><canvas id="chartEstadoEstudiantes"></canvas></div>
        </div>
      </div>
    </div>
  </div>
</main>
