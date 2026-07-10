<main class="main-content">
  <div class="mb-4">
    <h4 class="mb-1"><i class="bi bi-mortarboard me-2"></i>Inscripción de Estudiantes</h4>
    <p class="text-muted mb-0">Selecciona una sección y arrastra los estudiantes para inscribirlos</p>
  </div>

  <div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body p-4">
      <div class="row g-3 align-items-end">
        <div class="col-md-5">
          <label for="selectPeriodo" class="form-label fw-semibold text-secondary small">
            <i class="bi bi-calendar-range me-1"></i>Período Académico
          </label>
          <div class="input-group">
            <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-calendar-range"></i></span>
            <select class="form-select border-start-0 ps-0" id="selectPeriodo">
              <option value="">Seleccionar período</option>
            </select>
          </div>
        </div>
        <div class="col-md-5">
          <label for="selectSeccion" class="form-label fw-semibold text-secondary small">
            <i class="bi bi-calendar2-week me-1"></i>Sección
          </label>
          <div class="input-group">
            <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-calendar2-week"></i></span>
            <select class="form-select border-start-0 ps-0" id="selectSeccion" disabled>
              <option value="">Primero selecciona un período</option>
            </select>
          </div>
        </div>
        <div class="col-md-2 d-grid">
          <button class="btn btn-outline-secondary fw-semibold" id="btnLimpiar" type="button">
            <i class="bi bi-arrow-counterclockwise me-1"></i>Limpiar
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="card border-0 shadow-sm rounded-3" id="cardInscripcion" style="display:none;">
    <div class="card-header bg-white border-bottom d-flex flex-wrap align-items-center justify-content-between gap-2 py-3 px-4">
      <div>
        <h5 class="mb-0 fw-bold" id="tituloSeccion">—</h5>
        <small class="text-muted" id="subtituloSeccion"></small>
      </div>
      <button class="btn text-white fw-semibold px-4" id="btnGuardar" style="background: #1a2a4a;">
        <i class="bi bi-check-lg me-1"></i>Guardar Inscripciones
      </button>
    </div>
    <div class="card-body p-4">
      <div class="dual-list-wrapper">
        <div class="card dual-list-column border rounded-3 overflow-hidden">
          <div class="dual-list-header" style="background: #f8f9fc;">
            <h6><i class="bi bi-people me-1 text-success"></i>Disponibles</h6>
            <span class="badge bg-success dual-list-badge" id="countDisponibles">0</span>
          </div>
          <div class="dual-list-search">
            <input type="text" id="searchDisponibles" placeholder="Buscar estudiante...">
          </div>
          <div class="dual-list-items" id="listaDisponibles">
            <div class="dual-list-empty">
              <i class="bi bi-inbox"></i>
              <span>No hay estudiantes disponibles</span>
            </div>
          </div>
        </div>

        <div class="card dual-list-column border rounded-3 overflow-hidden">
          <div class="dual-list-header" style="background: #f8f9fc;">
            <h6><i class="bi bi-mortarboard me-1 text-primary"></i>Inscritos</h6>
            <span class="badge bg-primary dual-list-badge" id="countInscritos">0</span>
          </div>
          <div class="dual-list-search">
            <input type="text" id="searchInscritos" placeholder="Buscar estudiante...">
          </div>
          <div class="dual-list-items" id="listaInscritos">
            <div class="dual-list-empty">
              <i class="bi bi-inbox"></i>
              <span>Arrastra estudiantes aquí</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
