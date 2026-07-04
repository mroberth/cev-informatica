<main class="main-content">
  <div class="mb-4">
    <h4 class="mb-1"><i class="bi bi-calendar-range me-2"></i>Crear Período Académico</h4>
    <p class="text-muted mb-0">Registra un nuevo período académico (semestre) para la planificación</p>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
          <form id="formCrearPeriodo" novalidate>
            <div class="row g-3">

              <div class="col-md-6 mb-2">
                <label for="nombre" class="form-label fw-semibold text-secondary small">
                  <i class="bi bi-tag me-1"></i>Nombre del Período
                </label>
                <div class="input-group has-validation">
                  <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-tag"></i></span>
                  <input type="text" class="form-control border-start-0 ps-0" id="nombre" name="nombre" placeholder="Ej: 2026-I" maxlength="10">
                  <div class="invalid-feedback" id="nombreError"></div>
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
                    <option value="Activo">Activo</option>
                    <option value="Inactivo">Inactivo</option>
                  </select>
                  <div class="invalid-feedback" id="estadoError"></div>
                </div>
              </div>

              <div class="col-md-6 mb-2">
                <label for="fecha_inicio" class="form-label fw-semibold text-secondary small">
                  <i class="bi bi-calendar-plus me-1"></i>Fecha de Inicio
                </label>
                <div class="input-group has-validation">
                  <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-calendar-plus"></i></span>
                  <input type="date" class="form-control border-start-0 ps-0" id="fecha_inicio" name="fecha_inicio">
                  <div class="invalid-feedback" id="fecha_inicioError"></div>
                </div>
              </div>

              <div class="col-md-6 mb-2">
                <label for="fecha_fin" class="form-label fw-semibold text-secondary small">
                  <i class="bi bi-calendar-check me-1"></i>Fecha de Fin
                </label>
                <div class="input-group has-validation">
                  <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-calendar-check"></i></span>
                  <input type="date" class="form-control border-start-0 ps-0" id="fecha_fin" name="fecha_fin">
                  <div class="invalid-feedback" id="fecha_finError"></div>
                </div>
              </div>

            </div>

            <hr class="my-4">

            <div class="d-flex gap-2">
              <button type="submit" id="btn-guardar" class="btn text-white fw-semibold px-4" style="background: #1a2a4a;">
                <i class="bi bi-check-lg me-1"></i>Guardar
              </button>
              <a href="/a/periodos/consultar" class="btn btn-outline-secondary fw-semibold px-4">
                <i class="bi bi-x-lg me-1"></i>Cancelar
              </a>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</main>
