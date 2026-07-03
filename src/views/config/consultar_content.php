<main class="main-content">
  <div class="mb-4">
    <h4 class="mb-1"><i class="bi bi-search me-2"></i>Consultar Configuración</h4>
    <p class="text-muted mb-0">Administra roles, módulos y permisos registrados en el sistema</p>
  </div>

  <!-- Tabs -->
  <ul class="nav nav-tabs mb-4" id="configTabs" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="roles-tab" data-bs-toggle="tab" data-bs-target="#roles" type="button" role="tab" aria-controls="roles" aria-selected="true">
        <i class="bi bi-shield me-1"></i>Roles
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="modulos-tab" data-bs-toggle="tab" data-bs-target="#modulos" type="button" role="tab" aria-controls="modulos" aria-selected="false">
        <i class="bi bi-puzzle me-1"></i>Módulos
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="permisos-tab" data-bs-toggle="tab" data-bs-target="#permisos" type="button" role="tab" aria-controls="permisos" aria-selected="false">
        <i class="bi bi-key me-1"></i>Permisos
      </button>
    </li>
  </ul>

  <div class="tab-content" id="configTabsContent">

    <!-- ============ TAB ROLES ============ -->
    <div class="tab-pane fade show active" id="roles" role="tabpanel" aria-labelledby="roles-tab">
      <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-3 p-md-4">
          <div class="table-responsive">
            <table id="tablaRoles" class="table table-hover align-middle" style="width:100%">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nombre</th>
                  <th>Descripción</th>
                  <th>Editar</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- ============ TAB MODULOS ============ -->
    <div class="tab-pane fade" id="modulos" role="tabpanel" aria-labelledby="modulos-tab">
      <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-3 p-md-4">
          <div class="table-responsive">
            <table id="tablaModulos" class="table table-hover align-middle" style="width:100%">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nombre</th>
                  <th>Descripción</th>
                  <th>Editar</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- ============ TAB PERMISOS ============ -->
    <div class="tab-pane fade" id="permisos" role="tabpanel" aria-labelledby="permisos-tab">
      <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-3 p-md-4">
          <div class="table-responsive">
            <table id="tablaPermisos" class="table table-hover align-middle" style="width:100%">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nombre</th>
                  <th>Descripción</th>
                  <th>Editar</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

  </div>
</main>

<!-- Modal Editar Rol -->
<div class="modal fade" id="editarRolModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background: #1a2a4a; color: #fff;">
        <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Editar Rol</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="formEditarRol">
          <input type="hidden" id="edit_rol_id">
          <div class="mb-3">
            <label for="edit_rol_nombre" class="form-label fw-semibold text-secondary small">Nombre del Rol</label>
            <div class="input-group has-validation">
              <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-shield"></i></span>
              <input type="text" class="form-control border-start-0 ps-0" id="edit_rol_nombre" maxlength="50">
              <div class="invalid-feedback" id="edit_rol_nombreError"></div>
            </div>
          </div>
          <div class="mb-3">
            <label for="edit_rol_descripcion" class="form-label fw-semibold text-secondary small">Descripción</label>
            <div class="input-group has-validation">
              <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-card-text"></i></span>
              <textarea class="form-control border-start-0 ps-0" id="edit_rol_descripcion" rows="3" maxlength="255"></textarea>
              <div class="invalid-feedback" id="edit_rol_descripcionError"></div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn text-white fw-semibold" style="background: #1a2a4a;" id="btnGuardarEditarRol">
          <i class="bi bi-check-lg me-1"></i>Guardar Cambios
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Editar Módulo -->
<div class="modal fade" id="editarModuloModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background: #1a2a4a; color: #fff;">
        <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Editar Módulo</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="formEditarModulo">
          <input type="hidden" id="edit_mod_id">
          <div class="mb-3">
            <label for="edit_mod_nombre" class="form-label fw-semibold text-secondary small">Nombre del Módulo</label>
            <div class="input-group has-validation">
              <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-puzzle"></i></span>
              <input type="text" class="form-control border-start-0 ps-0" id="edit_mod_nombre" maxlength="50">
              <div class="invalid-feedback" id="edit_mod_nombreError"></div>
            </div>
          </div>
          <div class="mb-3">
            <label for="edit_mod_descripcion" class="form-label fw-semibold text-secondary small">Descripción</label>
            <div class="input-group has-validation">
              <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-card-text"></i></span>
              <textarea class="form-control border-start-0 ps-0" id="edit_mod_descripcion" rows="3" maxlength="255"></textarea>
              <div class="invalid-feedback" id="edit_mod_descripcionError"></div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn text-white fw-semibold" style="background: #1a2a4a;" id="btnGuardarEditarModulo">
          <i class="bi bi-check-lg me-1"></i>Guardar Cambios
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Editar Permiso -->
<div class="modal fade" id="editarPermisoModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background: #1a2a4a; color: #fff;">
        <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Editar Permiso</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="formEditarPermiso">
          <input type="hidden" id="edit_perm_id">
          <div class="mb-3">
            <label for="edit_perm_nombre" class="form-label fw-semibold text-secondary small">Nombre del Permiso</label>
            <div class="input-group has-validation">
              <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-key"></i></span>
              <input type="text" class="form-control border-start-0 ps-0" id="edit_perm_nombre" maxlength="20">
              <div class="invalid-feedback" id="edit_perm_nombreError"></div>
            </div>
          </div>
          <div class="mb-3">
            <label for="edit_perm_descripcion" class="form-label fw-semibold text-secondary small">Descripción</label>
            <div class="input-group has-validation">
              <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-card-text"></i></span>
              <textarea class="form-control border-start-0 ps-0" id="edit_perm_descripcion" rows="3" maxlength="100"></textarea>
              <div class="invalid-feedback" id="edit_perm_descripcionError"></div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn text-white fw-semibold" style="background: #1a2a4a;" id="btnGuardarEditarPermiso">
          <i class="bi bi-check-lg me-1"></i>Guardar Cambios
        </button>
      </div>
    </div>
  </div>
</div>
