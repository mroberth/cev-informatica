import { initAdminShell } from '/js/modules/admin/common.js';
import { apiClient } from '/js/api/client.js';
import { CevAlert } from '/js/utils/CevAlert.js';

let sortableDisponibles = null;
let sortableInscritos = null;

const cargarPeriodos = async () => {
  const select = document.getElementById('selectPeriodo');
  select.innerHTML = '<option value="">Cargando...</option>';
  try {
    const res = await apiClient.get('a/inscripciones/obtener_periodos');
    select.innerHTML = '<option value="">Seleccionar período</option>';
    res.data.forEach(p => {
      select.innerHTML += `<option value="${p.id}">${p.nombre}</option>`;
    });
    select.disabled = false;
  } catch (e) {
    select.innerHTML = '<option value="">Error al cargar períodos</option>';
    CevAlert.error({ title: 'Error', text: e.message, confirmButtonText: 'Aceptar' });
  }
};

const cargarSecciones = async (idPeriodo) => {
  const select = document.getElementById('selectSeccion');
  select.innerHTML = '<option value="">Cargando...</option>';
  select.disabled = true;
  try {
    const res = await apiClient.get(`a/inscripciones/obtener_secciones?id_periodo=${idPeriodo}`);
    select.innerHTML = '<option value="">Seleccionar sección</option>';
    res.data.forEach(s => {
      select.innerHTML += `<option value="${s.id}">${s.codigo_seccion} - ${s.trayecto} (${s.turno})</option>`;
    });
    select.disabled = false;
  } catch (e) {
    select.innerHTML = '<option value="">Error al cargar secciones</option>';
    CevAlert.error({ title: 'Error', text: e.message, confirmButtonText: 'Aceptar' });
  }
};

const renderEstudiante = (e, avatarColors) => {
  const inicial = `${e.nombre.charAt(0)}${e.apellido.charAt(0)}`;
  const color = avatarColors[e.id % avatarColors.length];
  return `
    <div class="dual-list-item" data-id="${e.id}">
      <div class="item-avatar" style="background:${color};">${inicial}</div>
      <div class="item-info">
        <div class="item-name">${e.nombre} ${e.apellido}</div>
        <div class="item-cedula">${e.tipo_cedula}${e.cedula}</div>
      </div>
    </div>
  `;
};

const actualizarContadores = () => {
  const disponibles = document.querySelectorAll('#listaDisponibles .dual-list-item:not(.dual-list-empty)').length;
  const inscritos = document.querySelectorAll('#listaInscritos .dual-list-item:not(.dual-list-empty)').length;
  document.getElementById('countDisponibles').textContent = disponibles;
  document.getElementById('countInscritos').textContent = inscritos;
};

const toggleEmptyMessage = (containerId) => {
  const container = document.getElementById(containerId);
  const items = container.querySelectorAll('.dual-list-item');
  const emptyMsg = container.querySelector('.dual-list-empty');
  if (!emptyMsg) return;

  // Check if there are any non-empty items (items that are actual student entries)
  const hasItems = items.length > 0 && Array.from(items).some(el => el.dataset.id);
  emptyMsg.style.display = hasItems ? 'none' : 'block';
};

const inicializarSortable = () => {
  const avatarColors = [
    '#0d6efd', '#198754', '#dc3545', '#fd7e14', '#6f42c1',
    '#20c997', '#e83e8c', '#17a2b8', '#ffc107', '#6610f2',
  ];

  const listaDisponibles = document.getElementById('listaDisponibles');
  const listaInscritos = document.getElementById('listaInscritos');

  if (sortableDisponibles) sortableDisponibles.destroy();
  if (sortableInscritos) sortableInscritos.destroy();

  const onSort = () => {
    toggleEmptyMessage('listaDisponibles');
    toggleEmptyMessage('listaInscritos');
    actualizarContadores();
  };

  sortableDisponibles = new Sortable(listaDisponibles, {
    group: 'estudiantes',
    animation: 200,
    ghostClass: 'sortable-ghost',
    chosenClass: 'sortable-chosen',
    dragClass: 'sortable-drag',
    onSort,
  });

  sortableInscritos = new Sortable(listaInscritos, {
    group: 'estudiantes',
    animation: 200,
    ghostClass: 'sortable-ghost',
    chosenClass: 'sortable-chosen',
    dragClass: 'sortable-drag',
    onSort,
  });
};

const construirListas = async (idSeccion) => {
  const card = document.getElementById('cardInscripcion');
  const tituloSeccion = document.getElementById('tituloSeccion');
  const subtituloSeccion = document.getElementById('subtituloSeccion');
  const listaDisponibles = document.getElementById('listaDisponibles');
  const listaInscritos = document.getElementById('listaInscritos');

  card.style.display = 'none';
  listaDisponibles.innerHTML = '';
  listaInscritos.innerHTML = '';

  try {
    const res = await apiClient.get(`a/inscripciones/obtener_datos_seccion?id_seccion=${idSeccion}`);
    const { seccion, disponibles, inscritos } = res.data;

    tituloSeccion.textContent = `Sección: ${seccion.codigo_seccion}`;
    subtituloSeccion.textContent = `${seccion.periodo} — ${seccion.trayecto} — Turno: ${seccion.turno}`;

    const avatarColors = [
      '#0d6efd', '#198754', '#dc3545', '#fd7e14', '#6f42c1',
      '#20c997', '#e83e8c', '#17a2b8', '#ffc107', '#6610f2',
    ];

    const disponiblesHtml = disponibles.length > 0
      ? disponibles.map(e => renderEstudiante(e, avatarColors)).join('')
      : '<div class="dual-list-empty"><i class="bi bi-inbox"></i><span>No hay estudiantes disponibles</span></div>';
    listaDisponibles.innerHTML = disponiblesHtml;

    const inscritosHtml = inscritos.length > 0
      ? inscritos.map(e => renderEstudiante(e, avatarColors)).join('')
      : '<div class="dual-list-empty"><i class="bi bi-inbox"></i><span>Arrastra estudiantes aquí</span></div>';
    listaInscritos.innerHTML = inscritosHtml;

    card.style.display = 'block';
    inicializarSortable();
    actualizarContadores();
  } catch (e) {
    CevAlert.error({ title: 'Error', text: e.message, confirmButtonText: 'Aceptar' });
  }
};

const guardarInscripciones = async () => {
  const idSeccion = parseInt(document.getElementById('selectSeccion').value);
  if (!idSeccion) return;

  const inscritos = document.querySelectorAll('#listaInscritos .dual-list-item');
  const estudiantes = [];
  inscritos.forEach(el => {
    const id = parseInt(el.dataset.id);
    if (id) estudiantes.push(id);
  });

  const btn = document.getElementById('btnGuardar');
  const originalText = btn.innerHTML;
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Guardando...';

  try {
    await apiClient.post('a/inscripciones/guardar', {
      id_seccion: idSeccion,
      estudiantes,
    });

    CevAlert.success({
      title: 'Inscripciones Guardadas',
      text: 'Las inscripciones se han guardado correctamente.',
      confirmButtonText: 'Aceptar',
    });

    construirListas(idSeccion);
  } catch (error) {
    CevAlert.error({
      title: 'Error al guardar',
      text: error.message || 'Ocurrió un error inesperado.',
      confirmButtonText: 'Aceptar',
    });
  } finally {
    btn.disabled = false;
    btn.innerHTML = originalText;
  }
};

const initBusqueda = () => {
  const searchDisponibles = document.getElementById('searchDisponibles');
  const searchInscritos = document.getElementById('searchInscritos');

  const filtrar = (input, containerId) => {
    const term = input.value.toLowerCase().trim();
    const container = document.getElementById(containerId);
    const items = container.querySelectorAll('.dual-list-item');
    items.forEach(el => {
      if (el.dataset.id) {
        const name = el.querySelector('.item-name')?.textContent?.toLowerCase() || '';
        const cedula = el.querySelector('.item-cedula')?.textContent?.toLowerCase() || '';
        el.style.display = (!term || name.includes(term) || cedula.includes(term)) ? '' : 'none';
      }
    });
  };

  searchDisponibles.addEventListener('input', () => filtrar(searchDisponibles, 'listaDisponibles'));
  searchInscritos.addEventListener('input', () => filtrar(searchInscritos, 'listaInscritos'));
};

const initCrearInscripciones = () => {
  initAdminShell();
  cargarPeriodos();
  initBusqueda();

  const selectPeriodo = document.getElementById('selectPeriodo');
  const selectSeccion = document.getElementById('selectSeccion');
  const btnGuardar = document.getElementById('btnGuardar');
  const btnLimpiar = document.getElementById('btnLimpiar');

  selectPeriodo.addEventListener('change', () => {
    const idPeriodo = parseInt(selectPeriodo.value);
    document.getElementById('cardInscripcion').style.display = 'none';
    selectSeccion.value = '';
    selectSeccion.disabled = true;
    if (idPeriodo) {
      cargarSecciones(idPeriodo);
    } else {
      selectSeccion.innerHTML = '<option value="">Primero selecciona un período</option>';
    }
  });

  selectSeccion.addEventListener('change', () => {
    const idSeccion = parseInt(selectSeccion.value);
    document.getElementById('cardInscripcion').style.display = 'none';
    if (idSeccion) {
      construirListas(idSeccion);
    }
  });

  btnGuardar.addEventListener('click', guardarInscripciones);

  btnLimpiar.addEventListener('click', () => {
    selectPeriodo.value = '';
    selectPeriodo.dispatchEvent(new Event('change'));
    document.getElementById('cardInscripcion').style.display = 'none';
  });
};

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initCrearInscripciones);
} else {
  initCrearInscripciones();
}
