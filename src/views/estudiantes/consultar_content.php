<main class="main-content">
  <div class="mb-4 d-flex flex-wrap align-items-center justify-content-between gap-2">
    <div>
      <h4 class="mb-1"><i class="bi bi-mortarboard me-2"></i>Consultar Estudiantes</h4>
      <p class="text-muted mb-0">Listado general de estudiantes registrados en el sistema</p>
    </div>
    <a href="/a/estudiantes/crear" class="btn text-white fw-semibold" style="background: #1a2a4a;">
      <i class="bi bi-plus-lg me-1"></i>Nuevo estudiante
    </a>
  </div>

  <div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-3 p-md-4">
      <div class="table-responsive">
        <table id="tablaEstudiantes" class="table table-hover align-middle" style="width:100%">
          <thead>
            <tr>
              <th>Cédula</th>
              <th>Nombres y Apellidos</th>
              <th>Estado Académico</th>
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

<div class="modal fade" id="modalEditarEstudiante" tabindex="-1" aria-labelledby="modalEditarEstudianteLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow rounded-3">
      <div class="modal-header border-bottom" style="background: #1a2a4a;">
        <h5 class="modal-title text-white fw-bold" id="modalEditarEstudianteLabel">
          <i class="bi bi-pencil-square me-2"></i>Editar Estudiante
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <form id="formEditarEstudiante" novalidate>
          <input type="hidden" id="editar_id" name="id">

          <div class="mb-3">
            <label for="editar_nombre_completo" class="form-label fw-semibold text-secondary small">
              <i class="bi bi-person me-1"></i>Estudiante
            </label>
            <input type="text" class="form-control bg-light" id="editar_nombre_completo" readonly>
          </div>

          <div class="mb-3">
            <label for="editar_estado_academico" class="form-label fw-semibold text-secondary small">
              <i class="bi bi-toggle-on me-1"></i>Estado Académico
            </label>
            <div class="input-group has-validation">
              <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-toggle-on"></i></span>
              <select class="form-select border-start-0 ps-0" id="editar_estado_academico">
                <option value="">Seleccionar estado</option>
                <option value="Activo">Activo</option>
                <option value="Egresado">Egresado</option>
                <option value="Retirado">Retirado</option>
              </select>
              <div class="invalid-feedback" id="editar_estado_academicoError"></div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer border-top bg-light">
        <button type="button" class="btn btn-outline-secondary fw-semibold" data-bs-dismiss="modal">
          <i class="bi bi-x-lg me-1"></i>Cancelar
        </button>
        <button type="submit" form="formEditarEstudiante" class="btn text-white fw-semibold px-4" style="background: #1a2a4a;">
          <i class="bi bi-check-lg me-1"></i>Actualizar
        </button>
      </div>
    </div>
  </div>
</div>
