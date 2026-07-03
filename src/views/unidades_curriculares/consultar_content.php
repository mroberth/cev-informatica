<main class="main-content">
  <div class="mb-4 d-flex flex-wrap align-items-center justify-content-between gap-2">
    <div>
      <h4 class="mb-1"><i class="bi bi-search me-2"></i>Consultar Unidades Curriculares</h4>
      <p class="text-muted mb-0">Listado general de materias registradas en el pensum</p>
    </div>
    <a href="/a/unidades-curriculares/crear" class="btn text-white fw-semibold" style="background: #1a2a4a;">
      <i class="bi bi-plus-lg me-1"></i>Nueva unidad curricular
    </a>
  </div>

  <div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-3 p-md-4">
      <div class="table-responsive">
        <table id="tablaUC" class="table table-hover align-middle" style="width:100%">
          <thead>
            <tr>
              <th>Código</th>
              <th>Nombre</th>
              <th>Trayecto</th>
              <th>Fase</th>
              <th>U. Crédito</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</main>

<div class="modal fade" id="modalEditarUC" tabindex="-1" aria-labelledby="modalEditarUCLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow rounded-3">
      <div class="modal-header border-bottom" style="background: #1a2a4a;">
        <h5 class="modal-title text-white fw-bold" id="modalEditarUCLabel">
          <i class="bi bi-pencil-square me-2"></i>Editar Unidad Curricular
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <form id="formEditarUC" novalidate>
          <input type="hidden" id="editar_id" name="id">

          <div class="row g-3">

            <div class="col-md-6 mb-2">
              <label for="editar_id_trayecto" class="form-label fw-semibold text-secondary small">
                <i class="bi bi-layers me-1"></i>Trayecto
              </label>
              <div class="input-group has-validation">
                <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-layers"></i></span>
                <select class="form-select border-start-0 ps-0" id="editar_id_trayecto">
                  <option value="">Seleccionar trayecto</option>
                </select>
                <div class="invalid-feedback" id="editar_id_trayectoError"></div>
              </div>
            </div>

            <div class="col-md-6 mb-2">
              <label for="editar_id_fase" class="form-label fw-semibold text-secondary small">
                <i class="bi bi-columns me-1"></i>Fase
              </label>
              <div class="input-group has-validation">
                <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-columns"></i></span>
                <select class="form-select border-start-0 ps-0" id="editar_id_fase">
                  <option value="">Primero selecciona un trayecto</option>
                </select>
                <div class="invalid-feedback" id="editar_id_faseError"></div>
              </div>
            </div>

            <div class="col-md-6 mb-2">
              <label for="editar_codigo" class="form-label fw-semibold text-secondary small">
                <i class="bi bi-upc-scan me-1"></i>Código
              </label>
              <div class="input-group has-validation">
                <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-upc-scan"></i></span>
                <input type="text" class="form-control border-start-0 ps-0" id="editar_codigo" maxlength="20">
                <div class="invalid-feedback" id="editar_codigoError"></div>
              </div>
            </div>

            <div class="col-md-6 mb-2">
              <label for="editar_unidades_credito" class="form-label fw-semibold text-secondary small">
                <i class="bi bi-star me-1"></i>Unidades de Crédito
              </label>
              <div class="input-group has-validation">
                <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-star"></i></span>
                <input type="number" class="form-control border-start-0 ps-0" id="editar_unidades_credito" min="1" max="20">
                <div class="invalid-feedback" id="editar_unidades_creditoError"></div>
              </div>
            </div>

            <div class="col-12 mb-2">
              <label for="editar_nombre" class="form-label fw-semibold text-secondary small">
                <i class="bi bi-book me-1"></i>Nombre
              </label>
              <div class="input-group has-validation">
                <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-book"></i></span>
                <input type="text" class="form-control border-start-0 ps-0" id="editar_nombre" maxlength="100">
                <div class="invalid-feedback" id="editar_nombreError"></div>
              </div>
            </div>

          </div>
        </form>
      </div>
      <div class="modal-footer border-top bg-light">
        <button type="button" class="btn btn-outline-secondary fw-semibold" data-bs-dismiss="modal">
          <i class="bi bi-x-lg me-1"></i>Cancelar
        </button>
        <button type="submit" form="formEditarUC" class="btn text-white fw-semibold px-4" style="background: #1a2a4a;">
          <i class="bi bi-check-lg me-1"></i>Actualizar
        </button>
      </div>
    </div>
  </div>
</div>
