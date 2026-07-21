import { apiClient } from '/js/api/client.js';
import { obtenerDatosUsuario } from '/js/modules/student/common.js';

const VALID_EXTENSIONS = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'zip', 'rar', 'jpg', 'jpeg', 'png'];
const MAX_FILE_SIZE = 10 * 1024 * 1024;

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

const ICONOS_POR_TIPO = {
  pdf: 'bi-filetype-pdf',
  documento: 'bi-file-earmark-text',
  enlace: 'bi-link-45deg',
  video: 'bi-film',
  otro: 'bi-file-earmark',
};

const COLORES_POR_TIPO = {
  pdf: '#dc3545',
  documento: '#0d6efd',
  enlace: '#198754',
  video: '#6f42c1',
  otro: '#6c757d',
};

const ICONOS_EV = {
  tarea: 'bi-journal-text',
  examen: 'bi-pencil-square',
  proyecto: 'bi-diagram-3',
  taller: 'bi-tools',
  otro: 'bi-clipboard-check',
};

const COLORES_EV = {
  tarea: '#0d6efd',
  examen: '#dc3545',
  proyecto: '#198754',
  taller: '#fd7e14',
  otro: '#6f42c1',
};

function obtenerIdMateriaDesdeUrl() {
  const segmentos = window.location.pathname.replace(/\/+$/, '').split('/');
  return parseInt(segmentos[segmentos.length - 1], 10);
}

async function cargarInfoMateria() {
  const materiaId = obtenerIdMateriaDesdeUrl();
  if (!materiaId) return;

  try {
    const res = await apiClient.get(`u/materia/${materiaId}/recursos`);
    const materia = res.materia || {};

    const nombreEl = document.getElementById('materiaNombre');
    const codigoEl = document.getElementById('materiaCodigo');
    const trayectoEl = document.getElementById('materiaTrayecto');
    const seccionEl = document.getElementById('materiaSeccion');

    if (nombreEl) nombreEl.textContent = materia.nombre || 'Materia';
    if (codigoEl) codigoEl.textContent = materia.codigo || '';
    if (trayectoEl) trayectoEl.textContent = materia.trayecto || '';
    if (seccionEl) seccionEl.textContent = materia.seccion || '';

    const recursos = res.data || [];
    renderizarRecursos(recursos);
  } catch {
    const container = document.getElementById('recursosContainer');
    if (container) {
      container.innerHTML = '<p class="cev-student-empty">Error al cargar los recursos.</p>';
    }
  }
}

function renderizarRecursos(recursos) {
  const container = document.getElementById('recursosContainer');
  if (!container) return;

  if (!recursos.length) {
    container.innerHTML = '<p class="cev-student-empty">No hay recursos disponibles para esta materia.</p>';
    return;
  }

  container.innerHTML = `
    <div class="cev-recursos-grid">
      ${recursos.map(r => crearTarjetaRecurso(r)).join('')}
    </div>
  `;
}

function crearTarjetaRecurso(recurso) {
  const icono = ICONOS_POR_TIPO[recurso.tipo] || ICONOS_POR_TIPO.otro;
  const color = COLORES_POR_TIPO[recurso.tipo] || COLORES_POR_TIPO.otro;

  const etiquetaTipo = recurso.tipo.charAt(0).toUpperCase() + recurso.tipo.slice(1);

  let contenido = '';
  let accion = '';

  if (recurso.enlace_url) {
    contenido = `<a href="${recurso.enlace_url}" target="_blank" rel="noopener noreferrer" class="cev-recurso-card">`;
    accion = 'Abrir enlace';
  } else if (recurso.archivo_ruta) {
    contenido = `<a href="${recurso.archivo_ruta}" target="_blank" class="cev-recurso-card">`;
    accion = 'Descargar';
  } else {
    contenido = `<div class="cev-recurso-card">`;
    accion = '';
  }

  const fecha = recurso.creado_en
    ? new Date(recurso.creado_en).toLocaleDateString('es-VE', { year: 'numeric', month: 'long', day: 'numeric' })
    : '';

  return `
    ${contenido}
      <div class="cev-recurso-icon" style="background:${color}20; color:${color}">
        <i class="bi ${icono}"></i>
      </div>
      <div class="cev-recurso-body">
        <strong class="cev-recurso-titulo">${recurso.titulo}</strong>
        ${recurso.descripcion ? `<p class="cev-recurso-descripcion">${recurso.descripcion}</p>` : ''}
        <div class="cev-recurso-meta">
          <span class="cev-recurso-tipo">${etiquetaTipo}</span>
          ${fecha ? `<span class="cev-recurso-fecha">${fecha}</span>` : ''}
          ${recurso.creado_por_nombre ? `<span class="cev-recurso-autor">${recurso.creado_por_nombre}</span>` : ''}
        </div>
      </div>
      ${accion ? `<div class="cev-recurso-action"><i class="bi bi-box-arrow-up-right"></i></div>` : ''}
    ${recurso.enlace_url || recurso.archivo_ruta ? '</a>' : '</div>'}
  `;
}

async function cargarEvaluaciones() {
  const materiaId = obtenerIdMateriaDesdeUrl();
  if (!materiaId) return;

  try {
    const res = await apiClient.get(`u/materia/${materiaId}/evaluaciones`);
    const evaluaciones = res.data || [];
    renderizarEvaluaciones(evaluaciones);
  } catch {
    const container = document.getElementById('evaluacionesContainer');
    if (container) {
      container.innerHTML = '<p class="cev-student-empty">Error al cargar las evaluaciones.</p>';
    }
  }
}

function renderizarEvaluaciones(evaluaciones) {
  const container = document.getElementById('evaluacionesContainer');
  if (!container) return;

  if (!evaluaciones.length) {
    container.innerHTML = '<p class="cev-student-empty">No hay evaluaciones o tareas para esta materia.</p>';
    return;
  }

  container.innerHTML = `
    <div class="cev-student-eval-grid">
      ${evaluaciones.map(e => crearTarjetaEvaluacion(e)).join('')}
    </div>
  `;
}

function crearTarjetaEvaluacion(e) {
  const icono = ICONOS_EV[e.tipo] || ICONOS_EV.otro;
  const color = COLORES_EV[e.tipo] || COLORES_EV.otro;
  const etiqueta = e.tipo.charAt(0).toUpperCase() + e.tipo.slice(1);
  const fecha = e.fecha_estimada
    ? new Date(e.fecha_estimada).toLocaleDateString('es-VE', { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' })
    : '';

  const tieneEntrega = e.entrega !== null;
  const entregaLabel = tieneEntrega ? 'Re-entregar' : 'Entregar';
  const entregaBadge = tieneEntrega
    ? '<span class="badge bg-success ms-2">Entregado</span>'
    : '';

  return `
    <div class="cev-student-eval-card">
      <div class="cev-student-eval-icon" style="background:${color}20; color:${color}">
        <i class="bi ${icono}"></i>
      </div>
      <div class="cev-student-eval-body">
        <strong>${e.titulo}${entregaBadge}</strong>
        ${e.descripcion ? `<p class="cev-student-eval-desc">${e.descripcion}</p>` : ''}
        <div class="cev-student-eval-meta">
          <span class="cev-student-eval-tipo">${etiqueta}</span>
          <span><i class="bi bi-calendar3"></i> ${fecha}</span>
          ${e.porcentaje ? `<span><i class="bi bi-percent"></i> ${e.porcentaje}%</span>` : ''}
        </div>
      </div>
      <button class="cev-student-eval-btn-entregar" title="${entregaLabel}" data-eval='${JSON.stringify(e).replace(/'/g, "&#39;")}'>
        <i class="bi bi-upload"></i> ${entregaLabel}
      </button>
    </div>
  `;
}

async function cargarNotas() {
  const materiaId = obtenerIdMateriaDesdeUrl();
  if (!materiaId) return;

  try {
    const res = await apiClient.get(`u/materia/${materiaId}/notas`);
    const notas = res.data || [];
    renderizarNotas(notas);
  } catch {
    const container = document.getElementById('notasContainer');
    if (container) {
      container.innerHTML = '<p class="cev-student-empty">Error al cargar las notas.</p>';
    }
  }
}

function renderizarNotas(notas) {
  const container = document.getElementById('notasContainer');
  if (!container) return;

  if (!notas.length) {
    container.innerHTML = '<p class="cev-student-empty">No hay evaluaciones registradas para esta materia.</p>';
    return;
  }

  container.innerHTML = `
    <div class="cev-student-notas-grid">
      ${notas.map(n => crearTarjetaNota(n)).join('')}
    </div>
  `;
}

function crearTarjetaNota(n) {
  const color = COLORES_EV[n.tipo] || COLORES_EV.otro;
  const etiqueta = n.tipo.charAt(0).toUpperCase() + n.tipo.slice(1);
  const fecha = n.fecha_estimada
    ? new Date(n.fecha_estimada).toLocaleDateString('es-VE', { year: 'numeric', month: 'long', day: 'numeric' })
    : '';
  const tieneNota = n.nota !== null && n.nota !== undefined;
  const notaClass = tieneNota ? (n.nota >= 10 ? 'nota-aprobada' : 'nota-reprobada') : 'nota-pendiente';

  return `
    <div class="cev-student-nota-card">
      <div class="cev-student-nota-left">
        <div class="cev-student-eval-icon" style="background:${color}20; color:${color}">
          <i class="bi ${ICONOS_EV[n.tipo] || ICONOS_EV.otro}"></i>
        </div>
        <div class="cev-student-nota-info">
          <strong>${n.titulo}</strong>
          <div class="cev-student-nota-meta">
            <span class="cev-student-eval-tipo">${etiqueta}</span>
            <span><i class="bi bi-calendar3"></i> ${fecha}</span>
            ${n.porcentaje ? `<span><i class="bi bi-percent"></i> ${n.porcentaje}%</span>` : ''}
          </div>
          ${n.observaciones ? `<p class="cev-student-nota-obs">${n.observaciones}</p>` : ''}
        </div>
      </div>
      <div class="cev-student-nota-right ${notaClass}">
        ${tieneNota ? n.nota.toFixed(1) : '—'}
      </div>
    </div>
  `;
}

// ===== ENTREGAS =====

function initEntregas() {
  document.getElementById('evaluacionesContainer')?.addEventListener('click', (e) => {
    const btn = e.target.closest('.cev-student-eval-btn-entregar');
    if (!btn) return;
    try {
      const evalData = JSON.parse(btn.dataset.eval);
      abrirModalEntrega(evalData);
    } catch { /* ignore */ }
  });

  document.getElementById('btnGuardarEntrega')?.addEventListener('click', guardarEntrega);
}

function abrirModalEntrega(evalData) {
  document.getElementById('entregaEvaluacionId').value = evalData.id;
  document.getElementById('entregaTituloEval').textContent = evalData.titulo;
  document.getElementById('formEntrega').reset();

  const entregaExistente = document.getElementById('entregaExistente');
  const archivoActual = document.getElementById('entregaArchivoActual');
  const fechaActual = document.getElementById('entregaFechaActual');

  if (evalData.entrega) {
    entregaExistente.classList.remove('d-none');
    archivoActual.innerHTML = `<a href="/${evalData.entrega.archivo_ruta}" target="_blank">${evalData.entrega.archivo_nombre_original}</a>`;
    fechaActual.textContent = 'Subido: ' + new Date(evalData.entrega.fecha_entrega).toLocaleString('es-VE');
  } else {
    entregaExistente.classList.add('d-none');
  }

  new bootstrap.Modal(document.getElementById('modalEntregar')).show();
}

async function guardarEntrega() {
  const evaluacionId = document.getElementById('entregaEvaluacionId').value;
  const archivoInput = document.getElementById('entregaArchivo');
  const comentario = document.getElementById('entregaComentario').value.trim();

  archivoInput.classList.remove('is-invalid');

  if (!archivoInput.files || !archivoInput.files[0]) {
    showError(archivoInput, 'Debes seleccionar un archivo.');
    return;
  }

  const file = archivoInput.files[0];
  const ext = file.name.split('.').pop()?.toLowerCase();

  if (!VALID_EXTENSIONS.includes(ext)) {
    showError(archivoInput, 'Tipo de archivo no permitido. Extensiones: ' + VALID_EXTENSIONS.join(', '));
    return;
  }

  if (file.size > MAX_FILE_SIZE) {
    showError(archivoInput, 'El archivo supera los 10 MB.');
    return;
  }

  clearError(archivoInput);

  const formData = new FormData();
  formData.append('archivo', file);
  if (comentario) formData.append('comentario_alumno', comentario);

  try {
    const token = localStorage.getItem('token');
    const response = await fetch(`/u/evaluaciones/${evaluacionId}/entregar`, {
      method: 'POST',
      headers: token ? { 'Authorization': `Bearer ${token}` } : {},
      body: formData,
    });

    const result = await response.json();

    if (!response.ok) {
      alert(result.error || 'Error al entregar.');
      return;
    }

    const modal = bootstrap.Modal.getInstance(document.getElementById('modalEntregar'));
    if (modal) modal.hide();
    cargarEvaluaciones();
  } catch {
    alert('Error de conexión al entregar.');
  }
}

function initTabs() {
  document.querySelectorAll('.cev-student-tab').forEach(tab => {
    tab.addEventListener('click', () => {
      document.querySelectorAll('.cev-student-tab').forEach(t => t.classList.remove('active'));
      document.querySelectorAll('.cev-tab-content').forEach(c => c.classList.remove('active'));
      tab.classList.add('active');
      const targetId = 'tab' + tab.dataset.tab.charAt(0).toUpperCase() + tab.dataset.tab.slice(1);
      const target = document.getElementById(targetId);
      if (target) target.classList.add('active');

      if (tab.dataset.tab === 'evaluaciones') cargarEvaluaciones();
      if (tab.dataset.tab === 'notas') cargarNotas();
    });
  });
}

document.addEventListener('DOMContentLoaded', () => {
  initTabs();
  initEntregas();
  cargarInfoMateria();
});
