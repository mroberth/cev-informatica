<main class="main-content">
  <div class="mb-4 d-flex flex-wrap align-items-center justify-content-between gap-2">
    <div>
      <h4 class="mb-1"><i class="bi bi-calendar2-week me-2"></i>Consultar Secciones</h4>
      <p class="text-muted mb-0">Listado general de secciones registradas en el período lectivo</p>
    </div>
    <a href="/a/secciones/crear" class="btn text-white fw-semibold" style="background: #1a2a4a;">
      <i class="bi bi-plus-lg me-1"></i>Nueva sección
    </a>
  </div>

  <div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-3 p-md-4">
      <div class="table-responsive">
        <table id="tablaSecciones" class="table table-hover align-middle" style="width:100%">
          <thead>
            <tr>
              <th>Código</th>
              <th>Período</th>
              <th>Trayecto</th>
              <th>Turno</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <!-- El contenido se cargará dinámicamente con DataTable en consultar.js -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</main>

<div class="modal fade" id="modalEditarSec" tabindex="-1" aria-labelledby="modalEditarSecLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow rounded-3">
      <div class="modal-header border-bottom" style="background: #1a2a4a;">
        <h5 class="modal-title text-white fw-bold" id="modalEditarSecLabel">
          <i class="bi bi-pencil-square me-2"></i>Editar Sección
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <form id="formEditarSec" novalidate>
          <input type="hidden" id="editar_id" name="id">

          <div class="row g-3">

            <div class="col-md-6 mb-2">
              <label for="editar_id_periodo" class="form-label fw-semibold text-secondary small">
                <i class="bi bi-calendar-event me-1"></i>Período Académico
              </label>
              <div class="input-group has-validation">
                <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-calendar-event"></i></span>
                <select class="form-select border-start-0 ps-0" id="editar_id_periodo">
                  <option value="">Seleccionar período</option>
                </select>
                <div class="invalid-feedback" id="editar_id_periodoError"></div>
              </div>
            </div>

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
              <label for="editar_codigo_seccion" class="form-label fw-semibold text-secondary small">
                <i class="bi bi-tag me-1"></i>Código de Sección
              </label>
              <div class="input-group has-validation">
                <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-tag"></i></span>
                <input type="text" class="form-control border-start-0 ps-0" id="editar_codigo_seccion" maxlength="20">
                <div class="invalid-feedback" id="editar_codigo_seccionError"></div>
              </div>
            </div>

            <div class="col-md-6 mb-2">
              <label for="editar_turno" class="form-label fw-semibold text-secondary small">
                <i class="bi bi-brightness-high me-1"></i>Turno
              </label>
              <div class="input-group has-validation">
                <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-brightness-high"></i></span>
                <select class="form-select border-start-0 ps-0" id="editar_turno">
                  <option value="Diurno">Diurno</option>
                </select>
                <div class="invalid-feedback" id="editar_turnoError"></div>
              </div>
            </div>

          </div>
        </form>
      </div>
      <div class="modal-footer border-top bg-light">
        <button type="button" class="btn btn-outline-secondary fw-semibold" data-bs-dismiss="modal">
          <i class="bi bi-x-lg me-1"></i>Cancelar
        </button>
        <button type="submit" form="formEditarSec" class="btn text-white fw-semibold px-4" style="background: #1a2a4a;">
          <i class="bi bi-check-lg me-1"></i>Actualizar
        </button>
      </div>
    </div>
  </div>
</div>
