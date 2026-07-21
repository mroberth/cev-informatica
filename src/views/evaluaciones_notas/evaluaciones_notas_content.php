<main class="main-content">
  <div class="mb-4 d-flex flex-wrap align-items-center justify-content-between gap-2">
    <div>
      <h4 class="mb-1"><i class="bi bi-journal-text me-2"></i>Evaluaciones y Notas</h4>
      <p class="text-muted mb-0">Consulta global de evaluaciones y calificaciones por materia y sección</p>
    </div>
  </div>

  <div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body p-3 p-md-4">
      <div class="row g-3 align-items-end">
        <div class="col-md-3">
          <label for="filtroTrayecto" class="form-label fw-semibold text-secondary small">Trayecto</label>
          <select id="filtroTrayecto" class="form-select form-select-sm">
            <option value="">Todos los trayectos</option>
          </select>
        </div>
        <div class="col-md-3">
          <label for="filtroSeccion" class="form-label fw-semibold text-secondary small">Sección</label>
          <select id="filtroSeccion" class="form-select form-select-sm">
            <option value="">Todas las secciones</option>
          </select>
        </div>
        <div class="col-md-3">
          <label for="filtroMateria" class="form-label fw-semibold text-secondary small">Unidad Curricular</label>
          <select id="filtroMateria" class="form-select form-select-sm">
            <option value="">Todas las materias</option>
          </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
          <button id="btnFiltrar" class="btn btn-primary btn-sm flex-fill" style="background:#1a2a4a;">
            <i class="bi bi-funnel me-1"></i>Filtrar
          </button>
          <button id="btnLimpiar" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-x-lg"></i>
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-3 p-md-4">
      <div class="table-responsive">
        <table id="tablaEvaluaciones" class="table table-hover align-middle" style="width:100%">
          <thead>
            <tr>
              <th>Materia</th>
              <th>Trayecto</th>
              <th>Sección</th>
              <th>Docente</th>
              <th>Evaluación</th>
              <th>Tipo</th>
              <th>%</th>
              <th>Fecha</th>
              <th>Calif.</th>
              <th>Prom.</th>
              <th>Acción</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</main>

<div class="modal fade" id="modalVerNotas" tabindex="-1" aria-labelledby="modalVerNotasLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content border-0 shadow rounded-3">
      <div class="modal-header border-bottom" style="background: #1a2a4a;">
        <h5 class="modal-title text-white fw-bold" id="modalVerNotasLabel">
          <i class="bi bi-journal-text me-2"></i>Notas — <span id="modalEvalTitulo"></span>
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <div class="mb-3 small text-muted" id="modalEvalMeta"></div>
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0" id="tablaEstudiantes">
            <thead>
              <tr>
                <th>#</th>
                <th>Estudiante</th>
                <th>Nota</th>
                <th>Observaciones</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer border-top bg-light">
        <button type="button" class="btn btn-outline-secondary fw-semibold" data-bs-dismiss="modal">
          <i class="bi bi-x-lg me-1"></i>Cerrar
        </button>
      </div>
    </div>
  </div>
</div>
