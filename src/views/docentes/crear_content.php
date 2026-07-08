<main class="main-content">
  <div class="mb-4">
    <h4 class="mb-1"><i class="bi bi-person-badge me-2"></i>Crear Docente</h4>
    <p class="text-muted mb-0">Registra un docente vinculándolo a un usuario existente del sistema</p>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
          <form id="formCrearDocente" novalidate>
            <div class="row g-3">

              <div class="col-12 mb-2">
                <label for="id_usuario" class="form-label fw-semibold text-secondary small">
                  <i class="bi bi-person me-1"></i>Usuario (Docente)
                </label>
                <div class="input-group has-validation">
                  <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-person"></i></span>
                  <select class="form-select border-start-0 ps-0" id="id_usuario" name="id_usuario">
                    <option value="">Seleccionar usuario</option>
                  </select>
                  <div class="invalid-feedback" id="id_usuarioError"></div>
                </div>
              </div>

              <div class="col-md-4 mb-2">
                <label for="tipo_cedula" class="form-label fw-semibold text-secondary small">
                  <i class="bi bi-card-text me-1"></i>Tipo Cédula
                </label>
                <div class="input-group has-validation">
                  <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-card-text"></i></span>
                  <select class="form-select border-start-0 ps-0" id="tipo_cedula" name="tipo_cedula" disabled>
                    <option value="V">V</option>
                    <option value="J">J</option>
                    <option value="E">E</option>
                  </select>
                  <div class="invalid-feedback" id="tipo_cedulaError"></div>
                </div>
              </div>

              <div class="col-md-4 mb-2">
                <label for="cedula" class="form-label fw-semibold text-secondary small">
                  <i class="bi bi-hash me-1"></i>Cédula
                </label>
                <div class="input-group has-validation">
                  <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-hash"></i></span>
                  <input type="text" class="form-control border-start-0 ps-0 bg-light" id="cedula" name="cedula" readonly placeholder="Se autocompleta al seleccionar usuario">
                  <div class="invalid-feedback" id="cedulaError"></div>
                </div>
              </div>

              <div class="col-md-4 mb-2">
                <label for="especialidad" class="form-label fw-semibold text-secondary small">
                  <i class="bi bi-bookmark me-1"></i>Especialidad
                </label>
                <div class="input-group has-validation">
                  <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-bookmark"></i></span>
                  <input type="text" class="form-control border-start-0 ps-0" id="especialidad" name="especialidad" placeholder="Ej: Redes, Programación" maxlength="100">
                  <div class="invalid-feedback" id="especialidadError"></div>
                </div>
              </div>

            </div>

            <hr class="my-4">

            <div class="d-flex gap-2">
              <button type="submit" id="btn-guardar" class="btn text-white fw-semibold px-4" style="background: #1a2a4a;">
                <i class="bi bi-check-lg me-1"></i>Guardar
              </button>
              <a href="/a/docentes/consultar" class="btn btn-outline-secondary fw-semibold px-4">
                <i class="bi bi-x-lg me-1"></i>Cancelar
              </a>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</main>
