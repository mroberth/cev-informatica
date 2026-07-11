import { initAdminShell } from '/js/modules/admin/common.js';
import { apiClient } from '/js/api/client.js';
import { CevAlert } from '/js/utils/CevAlert.js';

let roles = [];
let modulos = [];
let permisos = [];
let permisosActuales = {};

const cargarMatriz = async () => {
  try {
    const res = await apiClient.get('a/control-acceso/obtener_matriz');
    roles = res.data.roles;
    modulos = res.data.modulos;
    permisos = res.data.permisos;

    const selectRol = document.getElementById('selectRol');
    selectRol.innerHTML = '<option value="">Seleccionar rol</option>';
    roles.forEach(r => {
      selectRol.innerHTML += `<option value="${r.id}">${r.nombre_rol}</option>`;
    });
    selectRol.disabled = false;
  } catch (e) {
    CevAlert.error({ title: 'Error', text: 'No se pudieron cargar los datos: ' + e.message, confirmButtonText: 'Aceptar' });
  }
};

const cargarPermisosRol = async (idRol) => {
  const tbody = document.getElementById('tbodyPermisos');
  tbody.innerHTML = '<tr><td colspan="5" class="text-center py-3"><span class="spinner-border spinner-border-sm me-2"></span>Cargando...</td></tr>';

  try {
    const res = await apiClient.get(`a/control-acceso/obtener_permisos_rol?id_rol=${idRol}`);
    permisosActuales = {};
    res.data.forEach(p => {
      const key = `${p.id_modulo}-${p.id_permiso}`;
      permisosActuales[key] = true;
    });

    renderizarTabla();
  } catch (e) {
    CevAlert.error({ title: 'Error', text: e.message, confirmButtonText: 'Aceptar' });
  }
};

const renderizarTabla = () => {
  const tbody = document.getElementById('tbodyPermisos');
  tbody.innerHTML = '';

  modulos.forEach(mod => {
    const tr = document.createElement('tr');
    tr.className = 'module-row';
    tr.innerHTML = `
      <td>
        <div class="module-name">${mod.nombre}</div>
        <div class="module-desc">${mod.descripcion || ''}</div>
      </td>
    `;

    permisos.forEach(perm => {
      const key = `${mod.id}-${perm.id}`;
      const checked = permisosActuales[key] ? 'checked' : '';
      const td = document.createElement('td');
      td.className = 'permiso-cell';
      td.innerHTML = `<input type="checkbox" class="permiso-checkbox" data-modulo="${mod.id}" data-permiso="${perm.id}" ${checked}>`;
      tr.appendChild(td);
    });

    tbody.appendChild(tr);
  });

  actualizarSelectAll();
};

const actualizarSelectAll = () => {
  document.querySelectorAll('.select-all').forEach(cb => {
    const permisoId = parseInt(cb.dataset.permiso);
    const checkboxes = document.querySelectorAll(`.permiso-checkbox[data-permiso="${permisoId}"]:not(.select-all)`);
    const checked = document.querySelectorAll(`.permiso-checkbox[data-permiso="${permisoId}"]:not(.select-all):checked`);
    const total = checkboxes.length;

    if (checked.length === 0) {
      cb.checked = false;
      cb.indeterminate = false;
    } else if (checked.length === total) {
      cb.checked = true;
      cb.indeterminate = false;
    } else {
      cb.checked = false;
      cb.indeterminate = true;
    }
  });
};

const recolectarPermisos = () => {
  const checks = document.querySelectorAll('#tbodyPermisos .permiso-checkbox:checked');
  const permisos = [];
  checks.forEach(cb => {
    if (!cb.classList.contains('select-all')) {
      permisos.push({
        id_modulo: parseInt(cb.dataset.modulo),
        id_permiso: parseInt(cb.dataset.permiso),
      });
    }
  });
  return permisos;
};

const guardarPermisos = async () => {
  const idRol = parseInt(document.getElementById('selectRol').value);
  if (!idRol) return;

  const permisos = recolectarPermisos();

  const btn = document.getElementById('btnGuardar');
  const originalText = btn.innerHTML;
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Guardando...';

  try {
    await apiClient.post('a/control-acceso/guardar', {
      id_rol: idRol,
      permisos,
    });

    CevAlert.success({
      title: 'Permisos Guardados',
      text: 'Los permisos se han actualizado correctamente.',
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

const initControlAcceso = () => {
  initAdminShell();

  const selectRol = document.getElementById('selectRol');
  const btnGuardar = document.getElementById('btnGuardar');
  const btnLimpiar = document.getElementById('btnLimpiar');
  const matrizContainer = document.getElementById('matrizContainer');
  const sinRol = document.getElementById('sinRol');

  cargarMatriz();

  selectRol.addEventListener('change', () => {
    const idRol = parseInt(selectRol.value);
    if (idRol) {
      matrizContainer.style.display = 'block';
      sinRol.style.display = 'none';
      cargarPermisosRol(idRol);
    } else {
      matrizContainer.style.display = 'none';
      sinRol.style.display = 'block';
    }
  });

  document.getElementById('tbodyPermisos').addEventListener('change', (e) => {
    if (e.target.classList.contains('permiso-checkbox') && !e.target.classList.contains('select-all')) {
      actualizarSelectAll();
    }
  });

  document.querySelectorAll('.select-all').forEach(cb => {
    cb.addEventListener('change', () => {
      const permisoId = parseInt(cb.dataset.permiso);
      const checkboxes = document.querySelectorAll(`#tbodyPermisos .permiso-checkbox[data-permiso="${permisoId}"]`);
      checkboxes.forEach(c => { c.checked = cb.checked; });
      actualizarSelectAll();
    });
  });

  btnGuardar.addEventListener('click', guardarPermisos);

  btnLimpiar.addEventListener('click', () => {
    document.querySelectorAll('#tbodyPermisos .permiso-checkbox').forEach(cb => { cb.checked = false; });
    actualizarSelectAll();
  });
};

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initControlAcceso);
} else {
  initControlAcceso();
}
