<main class="main-content">
  <div class="mb-4">
    <h4 class="mb-1"><i class="bi bi-person-plus me-2"></i>Asignación de Carga Docente</h4>
    <p class="text-muted mb-0">Selecciona una sección y asigna los docentes a cada unidad curricular</p>
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

  <div class="card border-0 shadow-sm rounded-3" id="cardAsignacion" style="display:none;">
    <div class="card-header bg-white border-bottom d-flex flex-wrap align-items-center justify-content-between gap-2 py-3 px-4">
      <div>
        <h5 class="mb-0 fw-bold" id="tituloSeccion">—</h5>
        <small class="text-muted" id="subtituloSeccion"></small>
      </div>
      <button class="btn text-white fw-semibold px-4" id="btnGuardar" style="background: #1a2a4a;">
        <i class="bi bi-check-lg me-1"></i>Guardar Asignaciones
      </button>
    </div>
    <div class="card-body p-4">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="tablaAsignacion">
          <thead>
            <tr>
              <th style="width:15%">Código</th>
              <th style="width:35%">Unidad Curricular</th>
              <th style="width:10%">UC</th>
              <th style="width:40%">Docente Asignado</th>
            </tr>
          </thead>
          <tbody id="tbodyAsignacion">
          </tbody>
        </table>
      </div>
      <div class="text-center py-5" id="sinUcs">
        <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
        <p class="text-muted mb-0">No se encontraron unidades curriculares para el trayecto de esta sección.</p>
      </div>
    </div>
  </div>
</main>
