import { initAdminShell } from '/js/modules/admin/common.js';
import { apiClient } from '/js/api/client.js';
import { CevAlert } from '/js/utils/CevAlert.js';

let docentesCache = [];

const cargarPeriodos = async () => {
  const select = document.getElementById('selectPeriodo');
  select.innerHTML = '<option value="">Cargando...</option>';
  try {
    const res = await apiClient.get('a/asignacion-docente/obtener_periodos');
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
    const res = await apiClient.get(`a/asignacion-docente/obtener_secciones?id_periodo=${idPeriodo}`);
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

const cargarDocentes = async () => {
  if (docentesCache.length > 0) return;
  try {
    const res = await apiClient.get('a/asignacion-docente/obtener_docentes');
    docentesCache = res.data;
  } catch (e) {
    CevAlert.error({ title: 'Error', text: 'No se pudieron cargar los docentes: ' + e.message, confirmButtonText: 'Aceptar' });
  }
};

const construirTabla = async (idSeccion) => {
  const card = document.getElementById('cardAsignacion');
  const tbody = document.getElementById('tbodyAsignacion');
  const sinUcs = document.getElementById('sinUcs');
  const tituloSeccion = document.getElementById('tituloSeccion');
  const subtituloSeccion = document.getElementById('subtituloSeccion');

  card.style.display = 'none';
  sinUcs.style.display = 'none';
  tbody.innerHTML = '';

  try {
    const res = await apiClient.get(`a/asignacion-docente/obtener_datos_seccion?id_seccion=${idSeccion}`);
    const { seccion, ucs, asignaciones } = res.data;

    tituloSeccion.textContent = `Sección: ${seccion.codigo_seccion}`;
    subtituloSeccion.textContent = `${seccion.periodo} — ${seccion.trayecto} — Turno: ${seccion.turno}`;

    if (!ucs || ucs.length === 0) {
      card.style.display = 'block';
      sinUcs.style.display = 'block';
      return;
    }

    const optionsHtml = docentesCache.map(d =>
      `<option value="${d.id}">${d.nombre} ${d.apellido} - ${d.tipo_cedula}${d.cedula}</option>`
    ).join('');

    ucs.forEach(uc => {
      const docenteId = asignaciones[uc.id] || '';
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td><span class="badge bg-light text-dark fs-6">${uc.codigo}</span></td>
        <td class="fw-medium">${uc.nombre}</td>
        <td>${uc.unidades_credito}</td>
        <td>
          <select class="form-select form-select-sm select-docente" data-id-uc="${uc.id}">
            <option value="">Sin asignar</option>
            ${optionsHtml}
          </select>
        </td>
      `;
      tbody.appendChild(tr);

      if (docenteId) {
        tr.querySelector('.select-docente').value = docenteId;
      }
    });

    card.style.display = 'block';
  } catch (e) {
    CevAlert.error({ title: 'Error', text: e.message, confirmButtonText: 'Aceptar' });
  }
};

const guardarAsignaciones = async () => {
  const idSeccion = parseInt(document.getElementById('selectSeccion').value);
  if (!idSeccion) return;

  const selects = document.querySelectorAll('#tbodyAsignacion .select-docente');
  const asignaciones = [];

  selects.forEach(sel => {
    const idUc = parseInt(sel.dataset.idUc);
    const idDocente = parseInt(sel.value);
    asignaciones.push({
      id_unidad_curricular: idUc,
      id_docente: isNaN(idDocente) ? null : idDocente,
    });
  });

  const btn = document.getElementById('btnGuardar');
  const originalText = btn.innerHTML;
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Guardando...';

  try {
    await apiClient.post('a/asignacion-docente/guardar', {
      id_seccion: idSeccion,
      asignaciones,
    });

    CevAlert.success({
      title: 'Asignaciones Guardadas',
      text: 'Las asignaciones se han guardado correctamente.',
      confirmButtonText: 'Aceptar',
    });
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

const initCrearAsignacion = () => {
  initAdminShell();
  cargarPeriodos();
  cargarDocentes();

  const selectPeriodo = document.getElementById('selectPeriodo');
  const selectSeccion = document.getElementById('selectSeccion');
  const btnGuardar = document.getElementById('btnGuardar');
  const btnLimpiar = document.getElementById('btnLimpiar');

  selectPeriodo.addEventListener('change', () => {
    const idPeriodo = parseInt(selectPeriodo.value);
    document.getElementById('cardAsignacion').style.display = 'none';
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
    document.getElementById('cardAsignacion').style.display = 'none';
    if (idSeccion) {
      construirTabla(idSeccion);
    }
  });

  btnGuardar.addEventListener('click', guardarAsignaciones);

  btnLimpiar.addEventListener('click', () => {
    selectPeriodo.value = '';
    selectPeriodo.dispatchEvent(new Event('change'));
    document.getElementById('cardAsignacion').style.display = 'none';
  });
};

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initCrearAsignacion);
} else {
  initCrearAsignacion();
}
