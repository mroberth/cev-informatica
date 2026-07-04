<main class="main-content">
  <div class="mb-4 d-flex flex-wrap align-items-center justify-content-between gap-2">
    <div>
      <h4 class="mb-1"><i class="bi bi-calendar-range me-2"></i>Consultar Períodos Académicos</h4>
      <p class="text-muted mb-0">Listado general de períodos académicos registrados</p>
    </div>
    <a href="/a/periodos/crear" class="btn text-white fw-semibold" style="background: #1a2a4a;">
      <i class="bi bi-plus-lg me-1"></i>Nuevo período
    </a>
  </div>

  <div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-3 p-md-4">
      <div class="table-responsive">
        <table id="tablaPeriodos" class="table table-hover align-middle" style="width:100%">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Fecha Inicio</th>
              <th>Fecha Fin</th>
              <th>Estado</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</main>

<div class="modal fade" id="modalEditarPeriodo" tabindex="-1" aria-labelledby="modalEditarPeriodoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow rounded-3">
      <div class="modal-header border-bottom" style="background: #1a2a4a;">
        <h5 class="modal-title text-white fw-bold" id="modalEditarPeriodoLabel">
          <i class="bi bi-pencil-square me-2"></i>Editar Período Académico
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <form id="formEditarPeriodo" novalidate>
          <input type="hidden" id="editar_id" name="id">

          <div class="row g-3">

            <div class="col-md-6 mb-2">
              <label for="editar_nombre" class="form-label fw-semibold text-secondary small">
                <i class="bi bi-tag me-1"></i>Nombre del Período
              </label>
              <div class="input-group has-validation">
                <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-tag"></i></span>
                <input type="text" class="form-control border-start-0 ps-0" id="editar_nombre" maxlength="10">
                <div class="invalid-feedback" id="editar_nombreError"></div>
              </div>
            </div>

            <div class="col-md-6 mb-2">
              <label for="editar_estado" class="form-label fw-semibold text-secondary small">
                <i class="bi bi-toggle-on me-1"></i>Estado
              </label>
              <div class="input-group has-validation">
                <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-toggle-on"></i></span>
                <select class="form-select border-start-0 ps-0" id="editar_estado">
                  <option value="">Seleccionar estado</option>
                  <option value="Activo">Activo</option>
                  <option value="Inactivo">Inactivo</option>
                </select>
                <div class="invalid-feedback" id="editar_estadoError"></div>
              </div>
            </div>

            <div class="col-md-6 mb-2">
              <label for="editar_fecha_inicio" class="form-label fw-semibold text-secondary small">
                <i class="bi bi-calendar-plus me-1"></i>Fecha de Inicio
              </label>
              <div class="input-group has-validation">
                <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-calendar-plus"></i></span>
                <input type="date" class="form-control border-start-0 ps-0" id="editar_fecha_inicio">
                <div class="invalid-feedback" id="editar_fecha_inicioError"></div>
              </div>
            </div>

            <div class="col-md-6 mb-2">
              <label for="editar_fecha_fin" class="form-label fw-semibold text-secondary small">
                <i class="bi bi-calendar-check me-1"></i>Fecha de Fin
              </label>
              <div class="input-group has-validation">
                <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-calendar-check"></i></span>
                <input type="date" class="form-control border-start-0 ps-0" id="editar_fecha_fin">
                <div class="invalid-feedback" id="editar_fecha_finError"></div>
              </div>
            </div>

          </div>
        </form>
      </div>
      <div class="modal-footer border-top bg-light">
        <button type="button" class="btn btn-outline-secondary fw-semibold" data-bs-dismiss="modal">
          <i class="bi bi-x-lg me-1"></i>Cancelar
        </button>
        <button type="submit" form="formEditarPeriodo" class="btn text-white fw-semibold px-4" style="background: #1a2a4a;">
          <i class="bi bi-check-lg me-1"></i>Actualizar
        </button>
      </div>
    </div>
  </div>
</div>
