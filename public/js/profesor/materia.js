import { apiClient } from '/js/api/client.js';

const VALID_EXTENSIONS = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'zip', 'rar', 'jpg', 'jpeg', 'png'];
const MAX_FILE_SIZE = 10 * 1024 * 1024;

let asignacionId = 0;

function extraerIdUrl() {
  const segmentos = window.location.pathname.replace(/\/+$/, '').split('/');
  for (let i = segmentos.length - 1; i >= 0; i--) {
    const n = parseInt(segmentos[i], 10);
    if (!isNaN(n)) return n;
  }
  return 0;
}

const ICONOS_EV = {
  tarea: 'bi-check2-square',
  examen: 'bi-pencil-square',
  proyecto: 'bi-diagram-3',
  taller: 'bi-tools',
  otro: 'bi-calendar-event',
};
const COLORES_EV = {
  tarea: '#0d6efd',
  examen: '#dc3545',
  proyecto: '#198754',
  taller: '#fd7e14',
  otro: '#6f42c1',
};

const ICONOS_TIPO = {
  pdf: 'bi-filetype-pdf',
  documento: 'bi-file-earmark-text',
  enlace: 'bi-link-45deg',
  video: 'bi-film',
  otro: 'bi-file-earmark',
};
const COLORES_TIPO = {
  pdf: '#dc3545',
  documento: '#0d6efd',
  enlace: '#198754',
  video: '#6f42c1',
  otro: '#6c757d',
};

async function cargarRecursos() {
  asignacionId = extraerIdUrl();
  if (!asignacionId) return;

  try {
    const res = await apiClient.get(`p/materias/${asignacionId}/recursos`);
    const recursos = res.data || [];
    renderizarRecursos(recursos);
  } catch {
    const container = document.getElementById('recursosLista');
    if (container) container.innerHTML = '<p class="cev-prof-empty">Error al cargar recursos.</p>';
  }
}

function renderizarRecursos(recursos) {
  const container = document.getElementById('recursosLista');
  if (!container) return;

  if (!recursos.length) {
    container.innerHTML = '<p class="cev-prof-empty">No hay recursos todav&iacute;a. Agrega el primero.</p>';
    return;
  }

  container.innerHTML = `
    <div class="cev-prof-recursos-lista">
      ${recursos.map(r => `
        <div class="cev-prof-recurso-item" data-id="${r.id}">
          <div class="cev-prof-recurso-icon" style="background:${COLORES_TIPO[r.tipo] || COLORES_TIPO.otro}20; color:${COLORES_TIPO[r.tipo] || COLORES_TIPO.otro}">
            <i class="bi ${ICONOS_TIPO[r.tipo] || ICONOS_TIPO.otro}"></i>
          </div>
          <div class="cev-prof-recurso-body">
            <strong>${r.titulo}</strong>
            ${r.descripcion ? `<p class="cev-prof-recurso-desc">${r.descripcion}</p>` : ''}
            <div class="cev-prof-recurso-meta">
              <span class="cev-prof-recurso-tipo">${r.tipo}</span>
              ${r.archivo_ruta ? `<a href="${r.archivo_ruta}" target="_blank" class="cev-prof-recurso-link"><i class="bi bi-download"></i> Descargar</a>` : ''}
              ${r.enlace_url ? `<a href="${r.enlace_url}" target="_blank" class="cev-prof-recurso-link"><i class="bi bi-box-arrow-up-right"></i> Abrir enlace</a>` : ''}
            </div>
          </div>
          <button class="cev-prof-recurso-delete" title="Eliminar recurso" data-id="${r.id}">
            <i class="bi bi-trash3"></i>
          </button>
        </div>
      `).join('')}
    </div>
  `;

  document.querySelectorAll('.cev-prof-recurso-delete').forEach(btn => {
    btn.addEventListener('click', eliminarRecurso);
  });
}

async function eliminarRecurso(e) {
  const btn = e.currentTarget;
  const id = btn.dataset.id;

  if (!confirm('¿Eliminar este recurso?')) return;

  try {
    await apiClient.delete(`p/materias/recursos/${id}`);
    const item = btn.closest('.cev-prof-recurso-item');
    if (item) item.remove();

    const restantes = document.querySelectorAll('.cev-prof-recurso-item');
    if (!restantes.length) {
      document.getElementById('recursosLista').innerHTML = '<p class="cev-prof-empty">No hay recursos todav&iacute;a.</p>';
    }
  } catch {
    alert('Error al eliminar el recurso.');
  }
}

function initModal() {
  const selectTipo = document.getElementById('inputTipo');
  const campoArchivo = document.getElementById('campoArchivo');
  const campoEnlace = document.getElementById('campoEnlace');

  if (selectTipo) {
    selectTipo.addEventListener('change', () => {
      const esEnlace = selectTipo.value === 'enlace';
      campoArchivo.classList.toggle('d-none', esEnlace);
      campoEnlace.classList.toggle('d-none', !esEnlace);
    });
  }

  const btnGuardar = document.getElementById('btnGuardarRecurso');
  if (btnGuardar) {
    btnGuardar.addEventListener('click', guardarRecurso);
  }
}

async function guardarRecurso() {
  const validaciones = [validarRecursoTitulo(), validarRecursoArchivo()];
  if (!validaciones.every(v => v)) return;

  const titulo = document.getElementById('inputTitulo').value.trim();
  const tipo = document.getElementById('inputTipo').value;
  const descripcion = document.getElementById('inputDescripcion').value.trim();
  const enlace = document.getElementById('inputEnlace').value.trim();
  const archivoInput = document.getElementById('inputArchivo');
  const archivo = archivoInput?.files?.[0];

  const formData = new FormData();
  formData.append('titulo', titulo);
  formData.append('tipo', tipo);
  formData.append('descripcion', descripcion !== '' ? descripcion : '');
  if (tipo === 'enlace') {
    formData.append('enlace_url', enlace);
  } else if (archivo) {
    formData.append('archivo', archivo);
  }

  try {
    const token = localStorage.getItem('token');
    const response = await fetch(`/p/materias/${asignacionId}/recursos`, {
      method: 'POST',
      headers: token ? { 'Authorization': `Bearer ${token}` } : {},
      body: formData,
    });

    const result = await response.json();

    if (!response.ok) {
      alert(result.error || 'Error al guardar el recurso.');
      return;
    }

    const modal = bootstrap.Modal.getInstance(document.getElementById('modalRecurso'));
    if (modal) modal.hide();

    document.getElementById('formRecurso').reset();
    limpiarErrores();
    cargarRecursos();
  } catch {
    alert('Error de conexión al guardar.');
  }
}

const showError = (field, msg) => {
  const errorElement = document.getElementById(`${field.id}Error`);
  if (errorElement) errorElement.textContent = msg;
  field.classList.add('is-invalid');
  field.classList.remove('is-valid');
};

const clearError = (field) => {
  const errorElement = document.getElementById(`${field.id}Error`);
  if (errorElement) errorElement.textContent = '';
  field.classList.remove('is-invalid');
  field.classList.add('is-valid');
};

function limpiarErrores() {
  document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
  document.querySelectorAll('.is-valid').forEach(el => el.classList.remove('is-valid'));
}

function validarRecursoTitulo() {
  const field = document.getElementById('inputTitulo');
  if (!field.value.trim()) {
    showError(field, 'El título es obligatorio.');
    return false;
  }
  clearError(field);
  return true;
}

function validarRecursoArchivo() {
  const tipo = document.getElementById('inputTipo').value;
  const archivoInput = document.getElementById('inputArchivo');
  const enlaceField = document.getElementById('inputEnlace');

  if (tipo === 'enlace') {
    if (!enlaceField.value.trim()) {
      showError(enlaceField, 'Debes proporcionar un enlace.');
      return false;
    }
    try { new URL(enlaceField.value.trim()); } catch {
      showError(enlaceField, 'La URL no es válida.');
      return false;
    }
    clearError(enlaceField);
    return true;
  }

  clearError(enlaceField);

  if (!archivoInput.files || !archivoInput.files[0]) {
    showError(archivoInput, 'Debes seleccionar un archivo.');
    return false;
  }

  const file = archivoInput.files[0];
  const ext = file.name.split('.').pop()?.toLowerCase();

  if (!VALID_EXTENSIONS.includes(ext)) {
    showError(archivoInput, 'Tipo de archivo no permitido. Extensiones: ' + VALID_EXTENSIONS.join(', '));
    return false;
  }

  if (file.size > MAX_FILE_SIZE) {
    showError(archivoInput, 'El archivo supera los 10 MB.');
    return false;
  }

  clearError(archivoInput);
  return true;
}

// ===== EVALUACIONES =====

async function cargarEvaluaciones() {
  if (!asignacionId) return;
  try {
    const res = await apiClient.get(`p/materias/${asignacionId}/evaluaciones`);
    const evaluaciones = res.data || [];
    renderizarEvaluaciones(evaluaciones);
  } catch {
    const container = document.getElementById('evaluacionesLista');
    if (container) container.innerHTML = '<p class="cev-prof-empty">Error al cargar evaluaciones.</p>';
  }
}

function renderizarEvaluaciones(evaluaciones) {
  const container = document.getElementById('evaluacionesLista');
  if (!container) return;

  if (!evaluaciones.length) {
    container.innerHTML = '<p class="cev-prof-empty">No hay evaluaciones o tareas planificadas.</p>';
    return;
  }

  container.innerHTML = `
    <div class="cev-prof-recursos-lista">
      ${evaluaciones.map(e => {
        const fecha = e.fecha_estimada
          ? new Date(e.fecha_estimada).toLocaleDateString('es-VE', { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' })
          : '';
        const etiqueta = e.tipo.charAt(0).toUpperCase() + e.tipo.slice(1);
        return `
          <div class="cev-prof-recurso-item" data-id="${e.id}">
            <div class="cev-prof-recurso-icon" style="background:${COLORES_EV[e.tipo] || COLORES_EV.otro}20; color:${COLORES_EV[e.tipo] || COLORES_EV.otro}">
              <i class="bi ${ICONOS_EV[e.tipo] || ICONOS_EV.otro}"></i>
            </div>
            <div class="cev-prof-recurso-body">
              <strong>${e.titulo}</strong>
              ${e.descripcion ? `<p class="cev-prof-recurso-desc">${e.descripcion}</p>` : ''}
              <div class="cev-prof-recurso-meta">
                <span class="cev-prof-recurso-tipo">${etiqueta}</span>
                <span class="cev-prof-recurso-link"><i class="bi bi-calendar3"></i> ${fecha}</span>
                ${e.porcentaje ? `<span class="cev-prof-recurso-link"><i class="bi bi-percent"></i> ${e.porcentaje}%</span>` : ''}
              </div>
            </div>
            <div class="cev-prof-recurso-actions">
              <button class="cev-prof-recurso-btn" title="Ver entregas" data-id="${e.id}">
                <i class="bi bi-folder2-open"></i>
              </button>
              <button class="cev-prof-recurso-btn" title="Calificar" data-id="${e.id}">
                <i class="bi bi-check-circle"></i>
              </button>
              <button class="cev-prof-recurso-delete" title="Eliminar evaluaci&oacute;n" data-id="${e.id}">
                <i class="bi bi-trash3"></i>
              </button>
            </div>
          </div>
        `;
      }).join('')}
    </div>
  `;

  document.querySelectorAll('#evaluacionesLista .cev-prof-recurso-delete').forEach(btn => {
    btn.addEventListener('click', eliminarEvaluacion);
  });
  document.querySelectorAll('#evaluacionesLista .cev-prof-recurso-btn').forEach(btn => {
    const action = btn.querySelector('.bi-folder2-open') ? 'entregas' : 'calificar';
    btn.addEventListener('click', () => {
      if (action === 'entregas') abrirEntregas(btn.dataset.id);
      else abrirCalificar(btn.dataset.id);
    });
  });
}

async function eliminarEvaluacion(e) {
  const btn = e.currentTarget;
  const id = btn.dataset.id;
  if (!confirm('¿Eliminar esta evaluación/tarea?')) return;
  try {
    await apiClient.delete(`p/materias/evaluaciones/${id}`);
    const item = btn.closest('.cev-prof-recurso-item');
    if (item) item.remove();
    if (!document.querySelectorAll('#evaluacionesLista .cev-prof-recurso-item').length) {
      document.getElementById('evaluacionesLista').innerHTML = '<p class="cev-prof-empty">No hay evaluaciones o tareas planificadas.</p>';
    }
  } catch { alert('Error al eliminar.'); }
}

function validarEvTitulo() {
  const field = document.getElementById('evTitulo');
  if (!field.value.trim()) {
    showError(field, 'El título es obligatorio.');
    return false;
  }
  clearError(field);
  return true;
}

function validarEvFecha() {
  const field = document.getElementById('evFecha');
  if (!field.value) {
    showError(field, 'La fecha de entrega es obligatoria.');
    return false;
  }
  clearError(field);
  return true;
}

function validarEvPorcentaje() {
  const field = document.getElementById('evPorcentaje');
  const val = field.value.trim();
  if (val !== '') {
    const num = parseFloat(val);
    if (isNaN(num) || num < 1 || num > 100) {
      showError(field, 'El porcentaje debe estar entre 1 y 100.');
      return false;
    }
  }
  clearError(field);
  return true;
}

async function guardarEvaluacion() {
  const validaciones = [validarEvTitulo(), validarEvFecha(), validarEvPorcentaje()];
  if (!validaciones.every(v => v)) return;

  const titulo = document.getElementById('evTitulo').value.trim();
  const tipo = document.getElementById('evTipo').value;
  const descripcion = document.getElementById('evDescripcion').value.trim();
  const fecha = document.getElementById('evFecha').value;
  const porcentaje = document.getElementById('evPorcentaje').value;

  try {
    const res = await apiClient.post(`p/materias/${asignacionId}/evaluaciones`, {
      titulo, tipo, descripcion: descripcion || null, fecha_entrega: fecha,
      porcentaje: porcentaje || null,
    });

    const modal = bootstrap.Modal.getInstance(document.getElementById('modalEvaluacion'));
    if (modal) modal.hide();
    document.getElementById('formEvaluacion').reset();
    limpiarErrores();
    cargarEvaluaciones();
  } catch (err) {
    alert(err.error || 'Error al guardar la evaluación.');
  }
}

document.addEventListener('DOMContentLoaded', () => {
  initModal();
  cargarRecursos();
  cargarEvaluaciones();

  document.getElementById('btnNuevoRecurso')?.addEventListener('click', () => {
    new bootstrap.Modal(document.getElementById('modalRecurso')).show();
  });

  document.getElementById('btnNuevaEvaluacion')?.addEventListener('click', () => {
    new bootstrap.Modal(document.getElementById('modalEvaluacion')).show();
  });

  document.getElementById('btnGuardarEvaluacion')?.addEventListener('click', guardarEvaluacion);
  document.getElementById('btnGuardarCalificaciones')?.addEventListener('click', guardarCalificaciones);
});

// ===== CALIFICACIONES =====

async function abrirCalificar(evaluacionId) {
  const modal = new bootstrap.Modal(document.getElementById('modalCalificar'));
  document.getElementById('modalCalificarLabel').textContent = 'Calificar evaluación';
  const tbody = document.getElementById('calificacionesBody');
  tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">Cargando estudiantes...</td></tr>';
  modal.show();

  try {
    const res = await apiClient.get(`p/materias/evaluaciones/${evaluacionId}/calificaciones`);
    const estudiantes = res.data || [];
    document.getElementById('calificacionesEvaluacionId').value = evaluacionId;

    if (!estudiantes.length) {
      tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">No hay estudiantes inscritos.</td></tr>';
      return;
    }

    tbody.innerHTML = estudiantes.map(est => `
      <tr>
        <td>${est.nombre} ${est.apellido}</td>
        <td>
          <input type="number" class="form-control form-control-sm calif-nota"
                 step="0.5" min="0" max="20"
                 data-estudiante="${est.id_estudiante}"
                 value="${est.nota !== null && est.nota !== undefined ? est.nota : ''}"
                 placeholder="0-20">
        </td>
        <td>
          <input type="text" class="form-control form-control-sm calif-obs"
                 data-estudiante="${est.id_estudiante}"
                 value="${est.observaciones || ''}"
                 placeholder="Opcional">
        </td>
        <td class="text-center">
          ${est.nota !== null && est.nota !== undefined
            ? '<span class="badge bg-success">Calificado</span>'
            : '<span class="badge bg-secondary">Pendiente</span>'}
        </td>
      </tr>
    `).join('');
  } catch {
    tbody.innerHTML = '<tr><td colspan="4" class="text-center text-danger">Error al cargar estudiantes.</td></tr>';
  }
}

async function guardarCalificaciones() {
  const evaluacionId = document.getElementById('calificacionesEvaluacionId').value;
  if (!evaluacionId) return;

  const filas = document.querySelectorAll('#calificacionesBody tr');
  const calificaciones = [];
  let hasError = false;

  filas.forEach(tr => {
    const notaInput = tr.querySelector('.calif-nota');
    const obsInput = tr.querySelector('.calif-obs');
    if (!notaInput) return;
    const idEstudiante = parseInt(notaInput.dataset.estudiante, 10);
    if (!idEstudiante) return;
    const notaVal = notaInput.value.trim();

    notaInput.classList.remove('is-invalid');

    if (notaVal !== '') {
      const nota = parseFloat(notaVal);
      if (isNaN(nota) || nota < 0 || nota > 20) {
        notaInput.classList.add('is-invalid');
        hasError = true;
        return;
      }
    }

    calificaciones.push({
      id_estudiante: idEstudiante,
      nota: notaVal !== '' ? parseFloat(notaVal) : null,
      observaciones: obsInput ? obsInput.value.trim() || null : null,
    });
  });

  if (hasError) {
    alert('Corrige las notas inválidas (deben estar entre 0 y 20).');
    return;
  }

  if (!calificaciones.length) return;

  try {
    await apiClient.post(`p/materias/evaluaciones/${evaluacionId}/calificaciones`, { calificaciones });
    const modal = bootstrap.Modal.getInstance(document.getElementById('modalCalificar'));
    if (modal) modal.hide();
  } catch (err) {
    alert(err.error || 'Error al guardar calificaciones.');
  }
}

// ===== ENTREGAS =====

async function abrirEntregas(evaluacionId) {
  const modal = new bootstrap.Modal(document.getElementById('modalEntregas'));
  document.getElementById('modalEntregasLabel').textContent = 'Entregas de evaluación';
  const tbody = document.getElementById('entregasBody');
  tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">Cargando...</td></tr>';
  modal.show();

  try {
    const res = await apiClient.get(`p/materias/evaluaciones/${evaluacionId}/entregas`);
    const entregas = res.data || [];

    if (!entregas.length) {
      tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">No hay entregas aún.</td></tr>';
      return;
    }

    tbody.innerHTML = entregas.map(ent => `
      <tr>
        <td>${ent.nombre} ${ent.apellido}</td>
        <td>
          <a href="/${ent.archivo_ruta}" target="_blank" class="cev-prof-recurso-link">
            <i class="bi bi-download"></i> ${ent.archivo_nombre_original}
          </a>
        </td>
        <td>${ent.comentario_alumno || '—'}</td>
        <td class="text-nowrap">${new Date(ent.fecha_entrega).toLocaleString('es-VE')}</td>
      </tr>
    `).join('');
  } catch {
    tbody.innerHTML = '<tr><td colspan="4" class="text-center text-danger">Error al cargar entregas.</td></tr>';
  }
}
