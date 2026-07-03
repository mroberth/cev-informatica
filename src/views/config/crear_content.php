<main class="main-content">
  <div class="mb-4">
    <h4 class="mb-1"><i class="bi bi-plus-circle me-2"></i>Crear Configuración</h4>
    <p class="text-muted mb-0">Registra nuevos roles, módulos y permisos en el sistema</p>
  </div>

  <div class="row g-4">

    <!-- ============ ROLES ============ -->
    <div class="col-lg-4">
      <div class="card border-0 shadow-sm rounded-3 h-100">
        <div class="card-body p-4 d-flex flex-column">
          <h5 class="card-title mb-3"><i class="bi bi-shield me-2"></i>Nuevo Rol</h5>
          <form id="formRol" novalidate class="flex-grow-1 d-flex flex-column">
            <div class="mb-3">
              <label for="rol_nombre" class="form-label fw-semibold text-secondary small">
                <i class="bi bi-shield me-1"></i>Nombre del Rol
              </label>
              <div class="input-group has-validation">
                <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-shield"></i></span>
                <input type="text" class="form-control border-start-0 ps-0" id="rol_nombre" name="nombre_rol" placeholder="Ej: Coordinador" maxlength="50">
                <div class="invalid-feedback" id="rol_nombreError"></div>
              </div>
            </div>
            <div class="mb-3 flex-grow-1">
              <label for="rol_descripcion" class="form-label fw-semibold text-secondary small">
                <i class="bi bi-card-text me-1"></i>Descripción
              </label>
              <div class="input-group has-validation">
                <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-card-text"></i></span>
                <textarea class="form-control border-start-0 ps-0" id="rol_descripcion" name="descripcion" placeholder="Breve descripción del rol" rows="4" maxlength="255"></textarea>
                <div class="invalid-feedback" id="rol_descripcionError"></div>
              </div>
            </div>
            <button type="submit" class="btn text-white fw-semibold px-4 mt-auto" style="background: #1a2a4a;">
              <i class="bi bi-check-lg me-1"></i>Guardar Rol
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- ============ MODULOS ============ -->
    <div class="col-lg-4">
      <div class="card border-0 shadow-sm rounded-3 h-100">
        <div class="card-body p-4 d-flex flex-column">
          <h5 class="card-title mb-3"><i class="bi bi-puzzle me-2"></i>Nuevo Módulo</h5>
          <form id="formModulo" novalidate class="flex-grow-1 d-flex flex-column">
            <div class="mb-3">
              <label for="mod_nombre" class="form-label fw-semibold text-secondary small">
                <i class="bi bi-puzzle me-1"></i>Nombre del Módulo
              </label>
              <div class="input-group has-validation">
                <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-puzzle"></i></span>
                <input type="text" class="form-control border-start-0 ps-0" id="mod_nombre" name="nombre" placeholder="Ej: horarios" maxlength="50">
                <div class="invalid-feedback" id="mod_nombreError"></div>
              </div>
            </div>
            <div class="mb-3 flex-grow-1">
              <label for="mod_descripcion" class="form-label fw-semibold text-secondary small">
                <i class="bi bi-card-text me-1"></i>Descripción
              </label>
              <div class="input-group has-validation">
                <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-card-text"></i></span>
                <textarea class="form-control border-start-0 ps-0" id="mod_descripcion" name="descripcion" placeholder="Breve descripción del módulo" rows="4" maxlength="255"></textarea>
                <div class="invalid-feedback" id="mod_descripcionError"></div>
              </div>
            </div>
            <button type="submit" class="btn text-white fw-semibold px-4 mt-auto" style="background: #1a2a4a;">
              <i class="bi bi-check-lg me-1"></i>Guardar Módulo
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- ============ PERMISOS ============ -->
    <div class="col-lg-4">
      <div class="card border-0 shadow-sm rounded-3 h-100">
        <div class="card-body p-4 d-flex flex-column">
          <h5 class="card-title mb-3"><i class="bi bi-key me-2"></i>Nuevo Permiso</h5>
          <form id="formPermiso" novalidate class="flex-grow-1 d-flex flex-column">
            <div class="mb-3">
              <label for="perm_nombre" class="form-label fw-semibold text-secondary small">
                <i class="bi bi-key me-1"></i>Nombre del Permiso
              </label>
              <div class="input-group has-validation">
                <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-key"></i></span>
                <input type="text" class="form-control border-start-0 ps-0" id="perm_nombre" name="nombre" placeholder="Ej: imprimir" maxlength="20">
                <div class="invalid-feedback" id="perm_nombreError"></div>
              </div>
            </div>
            <div class="mb-3 flex-grow-1">
              <label for="perm_descripcion" class="form-label fw-semibold text-secondary small">
                <i class="bi bi-card-text me-1"></i>Descripción
              </label>
              <div class="input-group has-validation">
                <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-card-text"></i></span>
                <textarea class="form-control border-start-0 ps-0" id="perm_descripcion" name="descripcion" placeholder="Breve descripción del permiso" rows="4" maxlength="100"></textarea>
                <div class="invalid-feedback" id="perm_descripcionError"></div>
              </div>
            </div>
            <button type="submit" class="btn text-white fw-semibold px-4 mt-auto" style="background: #1a2a4a;">
              <i class="bi bi-check-lg me-1"></i>Guardar Permiso
            </button>
          </form>
        </div>
      </div>
    </div>

  </div>
</main>
