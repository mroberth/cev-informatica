<div class="cev-student-page-header">
  <a href="/u/mis-cursos" class="cev-student-back-link">&larr; Mis Cursos</a>
  <h1 id="materiaNombre">Materia</h1>
  <p id="materiaMeta">
    <span id="materiaCodigo"></span> &middot;
    <span id="materiaTrayecto"></span> &middot;
    Secci&oacute;n <span id="materiaSeccion"></span>
  </p>
</div>

<div class="cev-student-materia-tabs">
  <button class="cev-student-tab active" data-tab="recursos">
    <i class="bi bi-files"></i> Recursos
  </button>
  <button class="cev-student-tab" data-tab="evaluaciones">
    <i class="bi bi-clipboard-check"></i> Evaluaciones
  </button>
  <button class="cev-student-tab" data-tab="notas">
    <i class="bi bi-bar-chart"></i> Notas
  </button>
</div>

<div class="cev-tab-content active" id="tabRecursos">
  <div id="recursosContainer">
    <p class="cev-student-empty">Cargando recursos...</p>
  </div>
</div>

<div class="cev-tab-content" id="tabEvaluaciones">
  <div id="evaluacionesContainer">
    <p class="cev-student-empty">Cargando evaluaciones...</p>
  </div>
</div>

<div class="cev-tab-content" id="tabNotas">
  <div id="notasContainer">
    <p class="cev-student-empty">Cargando notas...</p>
  </div>
</div>

<!-- Modal para entregar tarea -->
<div class="modal fade" id="modalEntregar" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Entregar evaluación</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="entregaEvaluacionId">
        <p><strong id="entregaTituloEval"></strong></p>
        <form id="formEntrega">
          <div class="mb-3">
            <label class="form-label">Archivo (PDF, DOC, ZIP, etc.)</label>
            <input type="file" class="form-control" id="entregaArchivo" required>
            <div class="invalid-feedback" id="entregaArchivoError"></div>
          </div>
          <div class="mb-3">
            <label class="form-label">Comentario (opcional)</label>
            <textarea class="form-control" id="entregaComentario" rows="2"></textarea>
          </div>
        </form>
        <div id="entregaExistente" class="d-none">
          <hr>
          <p class="mb-1"><strong>Entrega actual:</strong></p>
          <p id="entregaArchivoActual" class="mb-1"></p>
          <p id="entregaFechaActual" class="text-muted small"></p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnGuardarEntrega">
          <i class="bi bi-upload"></i> Entregar
        </button>
      </div>
    </div>
  </div>
</div>
