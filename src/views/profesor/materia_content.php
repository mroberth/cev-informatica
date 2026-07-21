<div class="cev-prof-page-header">
  <a href="/p/materias" class="cev-prof-back-link">&larr; Mis Materias</a>
  <h1 id="matNombre">Materia</h1>
  <p id="matMeta">
    <span id="matCodigo"></span> &middot;
    <span id="matTrayecto"></span> &middot;
    Secci&oacute;n <span id="matSeccion"></span> &middot;
    <span id="matPeriodo"></span>
  </p>
</div>

<div class="cev-prof-toolbar">
  <button class="cev-prof-btn cev-prof-btn-primary" id="btnNuevoRecurso">
    <i class="bi bi-plus-lg"></i> Agregar recurso
  </button>
</div>

<div id="recursosLista">
  <p class="cev-prof-empty">Cargando recursos...</p>
</div>

<hr class="my-4">

<div class="cev-prof-section-header">
  <h2>Evaluaciones y Tareas</h2>
  <button class="cev-prof-btn cev-prof-btn-primary" id="btnNuevaEvaluacion">
    <i class="bi bi-plus-lg"></i> Agregar evaluaci&oacute;n
  </button>
</div>

<div id="evaluacionesLista">
  <p class="cev-prof-empty">Cargando evaluaciones...</p>
</div>

<!-- Modal para crear recurso -->
<div class="modal fade" id="modalRecurso" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Nuevo recurso</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="formRecurso">
          <div class="mb-3">
            <label class="form-label">Tipo</label>
            <select class="form-select" id="inputTipo" required>
              <option value="pdf">PDF</option>
              <option value="documento">Documento</option>
              <option value="video">Video</option>
              <option value="enlace">Enlace</option>
              <option value="otro">Otro</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">T&iacute;tulo</label>
            <input type="text" class="form-control" id="inputTitulo" required>
            <div class="invalid-feedback" id="inputTituloError"></div>
          </div>
          <div class="mb-3">
            <label class="form-label">Descripci&oacute;n</label>
            <textarea class="form-control" id="inputDescripcion" rows="2"></textarea>
          </div>
          <div class="mb-3" id="campoArchivo">
            <label class="form-label">Archivo</label>
            <input type="file" class="form-control" id="inputArchivo">
            <div class="invalid-feedback" id="inputArchivoError"></div>
          </div>
          <div class="mb-3 d-none" id="campoEnlace">
            <label class="form-label">URL del enlace</label>
            <input type="url" class="form-control" id="inputEnlace" placeholder="https://...">
            <div class="invalid-feedback" id="inputEnlaceError"></div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnGuardarRecurso">Guardar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal para crear evaluaci&oacute;n -->
<div class="modal fade" id="modalEvaluacion" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Nueva evaluaci&oacute;n / tarea</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="formEvaluacion">
          <div class="mb-3">
            <label class="form-label">Tipo</label>
            <select class="form-select" id="evTipo" required>
              <option value="tarea">Tarea</option>
              <option value="examen">Examen</option>
              <option value="proyecto">Proyecto</option>
              <option value="taller">Taller</option>
              <option value="otro">Otro</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">T&iacute;tulo</label>
            <input type="text" class="form-control" id="evTitulo" required>
            <div class="invalid-feedback" id="evTituloError"></div>
          </div>
          <div class="mb-3">
            <label class="form-label">Descripci&oacute;n</label>
            <textarea class="form-control" id="evDescripcion" rows="2"></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Fecha de entrega</label>
            <input type="datetime-local" class="form-control" id="evFecha" required>
            <div class="invalid-feedback" id="evFechaError"></div>
          </div>
          <div class="mb-3">
            <label class="form-label">Porcentaje de la nota (opcional)</label>
            <input type="number" class="form-control" id="evPorcentaje" min="1" max="100" placeholder="Ej: 20">
            <div class="invalid-feedback" id="evPorcentajeError"></div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnGuardarEvaluacion">Guardar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal para calificar -->
<div class="modal fade" id="modalCalificar" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalCalificarLabel">Calificar</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="calificacionesEvaluacionId">
        <div class="table-responsive">
          <table class="table table-sm table-bordered">
            <thead class="table-light">
              <tr>
                <th style="width:35%">Estudiante</th>
                <th style="width:15%">Nota (0-20)</th>
                <th style="width:35%">Observaciones</th>
                <th style="width:15%">Estado</th>
              </tr>
            </thead>
            <tbody id="calificacionesBody">
              <tr><td colspan="4" class="text-center text-muted">Cargando...</td></tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btnGuardarCalificaciones">
          <i class="bi bi-save"></i> Guardar calificaciones
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Modal para ver entregas -->
<div class="modal fade" id="modalEntregas" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEntregasLabel">Entregas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-sm table-bordered">
            <thead class="table-light">
              <tr>
                <th>Estudiante</th>
                <th>Archivo</th>
                <th>Comentario</th>
                <th>Fecha de entrega</th>
              </tr>
            </thead>
            <tbody id="entregasBody">
              <tr><td colspan="4" class="text-center text-muted">Cargando...</td></tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
