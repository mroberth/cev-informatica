<main class="main-content">
  <div class="mb-4 d-flex flex-wrap align-items-center justify-content-between gap-2">
    <div>
      <h4 class="mb-1"><i class="bi bi-search me-2"></i>Consultar Usuarios</h4>
      <p class="text-muted mb-0">Listado general de usuarios registrados en el sistema</p>
    </div>
    <a href="/a/usuarios/crear" class="btn text-white fw-semibold" style="background: #1a2a4a;">
      <i class="bi bi-plus-lg me-1"></i>Nuevo usuario
    </a>
  </div>

  <div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-3 p-md-4">
      <div class="table-responsive">
        <table id="tablaUsuarios" class="table table-hover align-middle" style="width:100%">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Apellido</th>
              <th>Correo</th>
              <th>Rol</th>
              <th>Estado</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</main>

<div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-labelledby="modalEditarUsuarioLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow rounded-3">
      <div class="modal-header border-bottom" style="background: #1a2a4a;">
        <h5 class="modal-title text-white fw-bold" id="modalEditarUsuarioLabel">
          <i class="bi bi-pencil-square me-2"></i>Editar Usuario
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <form id="formEditarUsuario" novalidate>
          <input type="hidden" id="editar_id" name="id">

          <div class="row g-3">

            <div class="col-md-6 mb-2">
              <label for="editar_nombre" class="form-label fw-semibold text-secondary small">
                <i class="bi bi-person me-1"></i>Nombre
              </label>
              <div class="input-group has-validation">
                <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-person"></i></span>
                <input type="text" class="form-control border-start-0 ps-0" id="editar_nombre" name="nombre" placeholder="Nombre">
                <div class="invalid-feedback" id="editar_nombreError"></div>
              </div>
            </div>

            <div class="col-md-6 mb-2">
              <label for="editar_apellido" class="form-label fw-semibold text-secondary small">
                <i class="bi bi-person me-1"></i>Apellido
              </label>
              <div class="input-group has-validation">
                <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-person"></i></span>
                <input type="text" class="form-control border-start-0 ps-0" id="editar_apellido" name="apellido" placeholder="Apellido">
                <div class="invalid-feedback" id="editar_apellidoError"></div>
              </div>
            </div>

            <div class="col-md-6 mb-2">
              <label for="editar_correo" class="form-label fw-semibold text-secondary small">
                <i class="bi bi-envelope me-1"></i>Correo electrónico
              </label>
              <div class="input-group has-validation">
                <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-envelope"></i></span>
                <input type="email" class="form-control border-start-0 ps-0" id="editar_correo" name="correo" placeholder="correo@ejemplo.com">
                <div class="invalid-feedback" id="editar_correoError"></div>
              </div>
            </div>

            <div class="col-md-6 mb-2">
              <label for="editar_password" class="form-label fw-semibold text-secondary small">
                <i class="bi bi-lock me-1"></i>Contraseña
              </label>
              <div class="input-group has-validation">
                <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-lock"></i></span>
                <input type="password" class="form-control border-start-0 ps-0" id="editar_password" name="password" placeholder="Dejar vacío para no cambiar">
                <div class="invalid-feedback" id="editar_passwordError"></div>              
              </div>
            </div>

            <div class="col-md-6 mb-2">
              <label for="editar_rol_id" class="form-label fw-semibold text-secondary small">
                <i class="bi bi-shield me-1"></i>Rol
              </label>
              <div class="input-group has-validation">
                <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-shield"></i></span>
                <select class="form-select border-start-0 ps-0" id="editar_rol_id" name="rol_id">
                  <option value="">Seleccionar rol</option>
                </select>
                <div class="invalid-feedback" id="editar_rol_idError"></div>
              </div>
            </div>

            <div class="col-md-6 mb-2">
              <label for="editar_estado" class="form-label fw-semibold text-secondary small">
                <i class="bi bi-toggle-on me-1"></i>Estado
              </label>
              <div class="input-group has-validation">
                <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-toggle-on"></i></span>
                <select class="form-select border-start-0 ps-0" id="editar_estado" name="estado">
                  <option value="">Seleccionar estado</option>
                  <option value="activo">Activo</option>
                  <option value="inactivo">Inactivo</option>
                </select>
                <div class="invalid-feedback" id="editar_estadoError"></div>
              </div>
            </div>

          </div>

        </form>
      </div>
      <div class="modal-footer border-top bg-light">
        <button type="button" class="btn btn-outline-secondary fw-semibold" data-bs-dismiss="modal">
          <i class="bi bi-x-lg me-1"></i>Cancelar
        </button>
        <button type="submit" form="formEditarUsuario" class="btn text-white fw-semibold px-4" style="background: #1a2a4a;">
          <i class="bi bi-check-lg me-1"></i>Actualizar
        </button>
      </div>
    </div>
  </div>
</div>
