<main class="main-content">
  <div class="mb-4">
    <h4 class="mb-1"><i class="bi bi-shield-lock me-2"></i>Control de Acceso</h4>
    <p class="text-muted mb-0">Gestiona los permisos de cada rol sobre los módulos del sistema</p>
  </div>

  <div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-4">
      <div class="row g-3 mb-4">
        <div class="col-md-6">
          <label for="selectRol" class="form-label fw-semibold text-secondary small">
            <i class="bi bi-person-badge me-1"></i>Seleccionar Rol
          </label>
          <div class="input-group">
            <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-person-badge"></i></span>
            <select class="form-select border-start-0 ps-0" id="selectRol">
              <option value="">Seleccionar rol</option>
            </select>
          </div>
        </div>
      </div>

      <div id="matrizContainer" style="display:none;">
        <div class="table-responsive">
          <table class="table table-bordered align-middle permisos-table mb-0">
            <thead class="table-light">
              <tr>
                <th style="width:30%">Módulo</th>
                <th class="permiso-col" data-permiso="1">Crear</th>
                <th class="permiso-col" data-permiso="2">Leer</th>
                <th class="permiso-col" data-permiso="3">Editar</th>
                <th class="permiso-col" data-permiso="4">Eliminar</th>
              </tr>
              <tr class="table-secondary">
                <td class="fw-semibold small">Seleccionar todos</td>
                <td class="select-all-cell"><input type="checkbox" class="permiso-checkbox select-all" data-permiso="1"></td>
                <td class="select-all-cell"><input type="checkbox" class="permiso-checkbox select-all" data-permiso="2"></td>
                <td class="select-all-cell"><input type="checkbox" class="permiso-checkbox select-all" data-permiso="3"></td>
                <td class="select-all-cell"><input type="checkbox" class="permiso-checkbox select-all" data-permiso="4"></td>
              </tr>
            </thead>
            <tbody id="tbodyPermisos">
            </tbody>
          </table>
        </div>

        <hr class="my-4">
        <div class="d-flex gap-2">
          <button class="btn text-white fw-semibold px-4" id="btnGuardar" style="background: #1a2a4a;">
            <i class="bi bi-check-lg me-1"></i>Guardar Permisos
          </button>
          <button class="btn btn-outline-secondary fw-semibold px-4" id="btnLimpiar">
            <i class="bi bi-arrow-counterclockwise me-1"></i>Limpiar todo
          </button>
        </div>
      </div>

      <div class="text-center py-5" id="sinRol">
        <i class="bi bi-shield-lock fs-1 text-muted d-block mb-2"></i>
        <p class="text-muted mb-0">Selecciona un rol para gestionar sus permisos</p>
      </div>
    </div>
  </div>
</main>
