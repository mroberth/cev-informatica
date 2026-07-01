<main class="main-content">
  <div class="mb-4">
    <h4 class="mb-1"><i class="bi bi-person-plus me-2"></i>Crear Usuario</h4>
    <p class="text-muted mb-0">Registra un nuevo usuario en el sistema</p>
  </div>

  <div class="row">
    <div class="col-12">

      <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">

          <form id="formCrearUsuario" novalidate>

            <div class="row g-3">

              <div class="col-md-6 mb-2">
                <label for="nombre" class="form-label fw-semibold text-secondary small">
                  <i class="bi bi-person me-1"></i>Nombre
                </label>
                <div class="input-group has-validation">
                  <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-person"></i></span>
                  <input type="text" class="form-control border-start-0 ps-0" id="nombre" name="nombre" placeholder="Nombre">
                  <div class="invalid-feedback" id="nombreError"></div>                
                </div>
              </div>

              <div class="col-md-6 mb-2">
                <label for="apellido" class="form-label fw-semibold text-secondary small">
                  <i class="bi bi-person me-1"></i>Apellido
                </label>
                <div class="input-group has-validation">
                  <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-person"></i></span>
                  <input type="text" class="form-control border-start-0 ps-0" id="apellido" name="apellido" placeholder="Apellido">
                  <div class="invalid-feedback" id="apellidoError"></div>
                </div>
              </div>

              <div class="col-md-6 mb-2">
                <label for="correo" class="form-label fw-semibold text-secondary small">
                  <i class="bi bi-envelope me-1"></i>Correo electrónico
                </label>
                <div class="input-group has-validation">
                  <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-envelope"></i></span>
                  <input type="email" class="form-control border-start-0 ps-0" id="correo" name="correo" placeholder="correo@ejemplo.com">
                  <div class="invalid-feedback" id="correoError"></div>
                </div>
              </div>

              <div class="col-md-6 mb-2">
                <label for="password" class="form-label fw-semibold text-secondary small">
                  <i class="bi bi-lock me-1"></i>Contraseña
                </label>
                <div class="input-group has-validation">
                  <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-lock"></i></span>
                  <input type="password" class="form-control border-start-0 ps-0" id="password" name="password" placeholder="Contraseña">
                  <div class="invalid-feedback" id="passwordError"></div>
                </div>
              </div>

              <div class="col-md-6 mb-2">
                <label for="rol_id" class="form-label fw-semibold text-secondary small">
                  <i class="bi bi-shield me-1"></i>Rol
                </label>
                <div class="input-group has-validation">
                  <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-shield"></i></span>
                  <select class="form-select border-start-0 ps-0" id="rol_id" name="rol_id">
                    <option value="">Seleccionar rol</option>
                  </select>
                  <div class="invalid-feedback" id="rol_idError"></div>
                </div>
              </div>

              <div class="col-md-6 mb-2">
                <label for="estado" class="form-label fw-semibold text-secondary small">
                  <i class="bi bi-toggle-on me-1"></i>Estado
                </label>
                <div class="input-group has-validation">
                  <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-toggle-on"></i></span>
                  <select class="form-select border-start-0 ps-0" id="estado" name="estado">
                    <option value="">Seleccionar estado</option>
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                  </select>
                  <div class="invalid-feedback" id="estadoError"></div>
                </div>
              </div>

            </div>

            <hr class="my-4">

            <div class="d-flex gap-2">
              <button type="submit" id="btn-guardar" class="btn text-white fw-semibold px-4" style="background: #1a2a4a;">
                <i class="bi bi-check-lg me-1"></i>Guardar
              </button>
              <a href="/a/usuarios/consultar" class="btn btn-outline-secondary fw-semibold px-4">
                <i class="bi bi-x-lg me-1"></i>Cancelar
              </a>
            </div>

          </form>

        </div>
      </div>

    </div>
  </div>
</main>