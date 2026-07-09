<main class="main-content">
  <div class="mb-4">
    <h4 class="mb-1"><i class="bi bi-book me-2"></i>Crear Unidad Curricular</h4>
    <p class="text-muted mb-0">Registra una nueva materia en el pensum académico</p>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
          <form id="formCrearUC" novalidate>
            <div class="row g-3">

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

              <div class="col-md-6 mb-2">
                <label class="form-label fw-semibold text-secondary small">
                  <i class="bi bi-columns me-1"></i>Fases
                </label>
                <div id="fases_container" class="border rounded p-3 bg-light" style="min-height: 44px;">
                  <span class="text-muted small">Primero selecciona un trayecto</span>
                </div>
                <div class="invalid-feedback" id="fasesError"></div>
              </div>

              <div class="col-md-6 mb-2">
                <label for="codigo" class="form-label fw-semibold text-secondary small">
                  <i class="bi bi-upc-scan me-1"></i>Código
                </label>
                <div class="input-group has-validation">
                  <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-upc-scan"></i></span>
                  <input type="text" class="form-control border-start-0 ps-0" id="codigo" name="codigo" placeholder="Ej: MAT-101" maxlength="20">
                  <div class="invalid-feedback" id="codigoError"></div>
                </div>
              </div>

              <div class="col-md-6 mb-2">
                <label for="unidades_credito" class="form-label fw-semibold text-secondary small">
                  <i class="bi bi-star me-1"></i>Unidades de Crédito
                </label>
                <div class="input-group has-validation">
                  <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-star"></i></span>
                  <input type="number" class="form-control border-start-0 ps-0" id="unidades_credito" name="unidades_credito" placeholder="Ej: 4" min="1" max="20">
                  <div class="invalid-feedback" id="unidades_creditoError"></div>
                </div>
              </div>

              <div class="col-12 mb-2">
                <label for="nombre" class="form-label fw-semibold text-secondary small">
                  <i class="bi bi-book me-1"></i>Nombre
                </label>
                <div class="input-group has-validation">
                  <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-book"></i></span>
                  <input type="text" class="form-control border-start-0 ps-0" id="nombre" name="nombre" placeholder="Nombre completo de la materia" maxlength="100">
                  <div class="invalid-feedback" id="nombreError"></div>
                </div>
              </div>

            </div>

            <hr class="my-4">

            <div class="d-flex gap-2">
              <button type="submit" id="btn-guardar" class="btn text-white fw-semibold px-4" style="background: #1a2a4a;">
                <i class="bi bi-check-lg me-1"></i>Guardar
              </button>
              <a href="/a/unidades-curriculares/consultar" class="btn btn-outline-secondary fw-semibold px-4">
                <i class="bi bi-x-lg me-1"></i>Cancelar
              </a>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</main>
