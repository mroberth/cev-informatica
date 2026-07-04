<main class="main-content">
  <div class="mb-4">
    <h4 class="mb-1"><i class="bi bi-calendar2-week me-2"></i>Crear Sección</h4>
    <p class="text-muted mb-0">Registra una nueva sección académica para el período lectivo</p>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
          <form id="formCrearSeccion" novalidate>
            <div class="row g-3">

              <!-- Período Académico -->
              <div class="col-md-6 mb-2">
                <label for="id_periodo" class="form-label fw-semibold text-secondary small">
                  <i class="bi bi-calendar-event me-1"></i>Período Académico
                </label>
                <div class="input-group has-validation">
                  <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-calendar-event"></i></span>
                  <select class="form-select border-start-0 ps-0" id="id_periodo" name="id_periodo">
                    <option value="">Seleccionar período</option>
                  </select>
                  <div class="invalid-feedback" id="id_periodoError"></div>
                </div>
              </div>

              <!-- Trayecto -->
              <div class="col-md-6 mb-2">
                <label for="id_trayecto" class="form-label fw-semibold text-secondary small">
                  <i class="bi bi-layers me-1"></i>Trayecto
                </label>
                <div class="input-group has-validation">
                  <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-layers"></i></span>
                  <select class="form-select border-start-0 ps-0" id="id_trayecto" name="id_trayecto">
                    <option value="">Seleccionar trayecto</option>
                  </select>
                  <div class="invalid-feedback" id="id_trayectoError"></div>
                </div>
              </div>

              <!-- Código Sección -->
              <div class="col-md-6 mb-2">
                <label for="codigo_seccion" class="form-label fw-semibold text-secondary small">
                  <i class="bi bi-tag me-1"></i>Código de Sección
                </label>
                <div class="input-group has-validation">
                  <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-tag"></i></span>
                  <input type="text" class="form-control border-start-0 ps-0" id="codigo_seccion" name="codigo_seccion" placeholder="Ej: INF-1101" maxlength="20">
                  <div class="invalid-feedback" id="codigo_seccionError"></div>
                </div>
              </div>

              <!-- Turno (Solo Diurno) -->
              <div class="col-md-6 mb-2">
                <label for="turno" class="form-label fw-semibold text-secondary small">
                  <i class="bi bi-brightness-high me-1"></i>Turno
                </label>
                <div class="input-group has-validation">
                  <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-brightness-high"></i></span>
                  <select class="form-select border-start-0 ps-0" id="turno" name="turno">
                    <option value="Diurno" selected>Diurno</option>
                  </select>
                  <div class="invalid-feedback" id="turnoError"></div>
                </div>
              </div>

            </div>

            <hr class="my-4">

            <div class="d-flex gap-2">
              <button type="submit" id="btn-guardar" class="btn text-white fw-semibold px-4" style="background: #1a2a4a;">
                <i class="bi bi-check-lg me-1"></i>Guardar
              </button>
              <a href="/a/secciones/consultar" class="btn btn-outline-secondary fw-semibold px-4">
                <i class="bi bi-x-lg me-1"></i>Cancelar
              </a>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</main>
