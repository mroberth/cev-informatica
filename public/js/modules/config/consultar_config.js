import { initAdminShell } from '/js/modules/admin/common.js';
import { apiClient } from '/js/api/client.js';
import { CevAlert } from '/js/utils/CevAlert.js';

const REGEX = /^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/;

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

const validarNombre = (field, min, max) => {
  const val = field.value.trim();
  if (val === '') {
    showError(field, 'Este campo es obligatorio.');
    return false;
  }
  if (val.length < min) {
    showError(field, `Debe tener al menos ${min} caracteres.`);
    return false;
  }
  if (val.length > max) {
    showError(field, `No puede exceder los ${max} caracteres.`);
    return false;
  }
  if (!REGEX.test(val)) {
    showError(field, 'El valor ingresado contiene caracteres no válidos.');
    return false;
  }
  clearError(field);
  return true;
};

const validarDescripcion = (field, max) => {
  const val = field.value.trim();
  if (val !== '' && val.length > max) {
    showError(field, `No puede exceder los ${max} caracteres.`);
    return false;
  }
  clearError(field);
  return true;
};

let dataTableRoles, dataTableModulos, dataTablePermisos;

const columnsRoles = [
  { data: 'id', visible: false },
  { data: 'nombre_rol' },
  {
    data: 'descripcion',
    render: (data) => data || '<span class="text-muted">—</span>'
  },
  {
    data: null,
    orderable: false,
    render: (row) => `<button class="btn btn-sm btn-outline-primary" onclick="editarRol(${row.id})" title="Editar"><i class="bi bi-pencil"></i></button>`
  }
];

const columnsModulos = [
  { data: 'id', visible: false },
  { data: 'nombre' },
  {
    data: 'descripcion',
    render: (data) => data || '<span class="text-muted">—</span>'
  },
  {
    data: null,
    orderable: false,
    render: (row) => `<button class="btn btn-sm btn-outline-primary" onclick="editarModulo(${row.id})" title="Editar"><i class="bi bi-pencil"></i></button>`
  }
];

const columnsPermisos = [
  { data: 'id', visible: false },
  { data: 'nombre' },
  {
    data: 'descripcion',
    render: (data) => data || '<span class="text-muted">—</span>'
  },
  {
    data: null,
    orderable: false,
    render: (row) => `<button class="btn btn-sm btn-outline-primary" onclick="editarPermiso(${row.id})" title="Editar"><i class="bi bi-pencil"></i></button>`
  }
];

const inicializarDataTable = (tableId, endpoint, columns) => {
  return new DataTable(tableId, {
    ajax: {
      url: endpoint,
      dataSrc: 'data',
    },
    columns,
    language: {
      url: '/plugins/datatables/es-ES.json'
    },
    responsive: true,
    autoWidth: false,
    order: [[0, 'asc']],
    pageLength: 10,
    dom: '<"d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3"Bf>rt<"d-flex flex-wrap align-items-center justify-content-between gap-2 pt-3"<"d-inline-flex"i><"d-inline-flex"p>>',
    buttons: [
      {
        extend: 'excelHtml5',
        text: '<i class="bi bi-file-earmark-excel me-1"></i>Excel',
        className: 'btn btn-success',
        titleAttr: 'Exportar a Excel',
        exportOptions: {
          columns: ':visible:not(:last-child)'
        }
      },
      {
        extend: 'pdfHtml5',
        text: '<i class="bi bi-file-earmark-pdf me-1"></i>PDF',
        className: 'btn btn-danger',
        titleAttr: 'Exportar a PDF',
        exportOptions: {
          columns: ':visible:not(:last-child)'
        }
      }
    ],
  });
};

// ==================== EDITAR ROL ====================
window.editarRol = async (id) => {
  try {
    const res = await apiClient.get('a/configuracion/obtener_roles');
    const rol = (res.data || []).find(r => r.id === id);
    if (!rol) return;

    document.getElementById('edit_rol_id').value = rol.id;
    document.getElementById('edit_rol_nombre').value = rol.nombre_rol;
    document.getElementById('edit_rol_descripcion').value = rol.descripcion || '';

    ['edit_rol_nombre', 'edit_rol_descripcion'].forEach(id => {
      document.getElementById(id).classList.remove('is-invalid', 'is-valid');
    });

    new bootstrap.Modal(document.getElementById('editarRolModal')).show();
  } catch (err) {
    CevAlert.error({ title: 'Error', text: err.message, confirmButtonText: 'Aceptar' });
  }
};

const initEditRol = () => {
  const nombre = document.getElementById('edit_rol_nombre');
  const descripcion = document.getElementById('edit_rol_descripcion');
  if (!nombre) return;

  nombre.addEventListener('input', () => validarNombre(nombre, 3, 50));

  nombre.addEventListener('blur', async () => {
    if (validarNombre(nombre, 3, 50)) {
      const idExcluir = parseInt(document.getElementById('edit_rol_id').value);
      const val = nombre.value.trim();
      if (!val) return;
      try {
        const res = await apiClient.get(`a/configuracion/verificar_rol?nombre=${encodeURIComponent(val)}&id_excluir=${idExcluir}`);
        if (res.existe) showError(nombre, 'Ya existe otro rol con ese nombre.');
        else clearError(nombre);
      } catch {}
    }
  });

  descripcion.addEventListener('input', () => validarDescripcion(descripcion, 255));

  document.getElementById('btnGuardarEditarRol')?.addEventListener('click', async () => {
    const id = parseInt(document.getElementById('edit_rol_id').value);
    const nombreOk = validarNombre(nombre, 3, 50);
    const descOk = validarDescripcion(descripcion, 255);
    if (!nombreOk || !descOk) return;

    try {
      const res = await apiClient.get(`a/configuracion/verificar_rol?nombre=${encodeURIComponent(nombre.value.trim())}&id_excluir=${id}`);
      if (res.existe) { showError(nombre, 'Ya existe otro rol con ese nombre.'); return; }
    } catch {}

    try {
      await apiClient.post('a/configuracion/actualizar_rol', {
        id, nombre_rol: nombre.value.trim(), descripcion: descripcion.value.trim()
      });
      CevAlert.success({ title: 'Actualización Exitosa', text: 'Rol actualizado correctamente.', confirmButtonText: 'Aceptar' });
      bootstrap.Modal.getInstance(document.getElementById('editarRolModal')).hide();
      dataTableRoles.ajax.reload(null, false);
    } catch (err) {
      CevAlert.error({ title: 'Error', text: err.message, confirmButtonText: 'Aceptar' });
    }
  });
};

// ==================== EDITAR MODULO ====================
window.editarModulo = async (id) => {
  try {
    const res = await apiClient.get('a/configuracion/obtener_modulos');
    const mod = (res.data || []).find(m => m.id === id);
    if (!mod) return;

    document.getElementById('edit_mod_id').value = mod.id;
    document.getElementById('edit_mod_nombre').value = mod.nombre;
    document.getElementById('edit_mod_descripcion').value = mod.descripcion || '';

    ['edit_mod_nombre', 'edit_mod_descripcion'].forEach(id => {
      document.getElementById(id).classList.remove('is-invalid', 'is-valid');
    });

    new bootstrap.Modal(document.getElementById('editarModuloModal')).show();
  } catch (err) {
    CevAlert.error({ title: 'Error', text: err.message, confirmButtonText: 'Aceptar' });
  }
};

const initEditModulo = () => {
  const nombre = document.getElementById('edit_mod_nombre');
  const descripcion = document.getElementById('edit_mod_descripcion');
  if (!nombre) return;

  nombre.addEventListener('input', () => validarNombre(nombre, 3, 50));

  nombre.addEventListener('blur', async () => {
    if (validarNombre(nombre, 3, 50)) {
      const idExcluir = parseInt(document.getElementById('edit_mod_id').value);
      const val = nombre.value.trim();
      if (!val) return;
      try {
        const res = await apiClient.get(`a/configuracion/verificar_modulo?nombre=${encodeURIComponent(val)}&id_excluir=${idExcluir}`);
        if (res.existe) showError(nombre, 'Ya existe otro módulo con ese nombre.');
        else clearError(nombre);
      } catch {}
    }
  });

  descripcion.addEventListener('input', () => validarDescripcion(descripcion, 255));

  document.getElementById('btnGuardarEditarModulo')?.addEventListener('click', async () => {
    const id = parseInt(document.getElementById('edit_mod_id').value);
    const nombreOk = validarNombre(nombre, 3, 50);
    const descOk = validarDescripcion(descripcion, 255);
    if (!nombreOk || !descOk) return;

    try {
      const res = await apiClient.get(`a/configuracion/verificar_modulo?nombre=${encodeURIComponent(nombre.value.trim())}&id_excluir=${id}`);
      if (res.existe) { showError(nombre, 'Ya existe otro módulo con ese nombre.'); return; }
    } catch {}

    try {
      await apiClient.post('a/configuracion/actualizar_modulo', {
        id, nombre: nombre.value.trim(), descripcion: descripcion.value.trim()
      });
      CevAlert.success({ title: 'Actualización Exitosa', text: 'Módulo actualizado correctamente.', confirmButtonText: 'Aceptar' });
      bootstrap.Modal.getInstance(document.getElementById('editarModuloModal')).hide();
      dataTableModulos.ajax.reload(null, false);
    } catch (err) {
      CevAlert.error({ title: 'Error', text: err.message, confirmButtonText: 'Aceptar' });
    }
  });
};

// ==================== EDITAR PERMISO ====================
window.editarPermiso = async (id) => {
  try {
    const res = await apiClient.get('a/configuracion/obtener_permisos');
    const perm = (res.data || []).find(p => p.id === id);
    if (!perm) return;

    document.getElementById('edit_perm_id').value = perm.id;
    document.getElementById('edit_perm_nombre').value = perm.nombre;
    document.getElementById('edit_perm_descripcion').value = perm.descripcion || '';

    ['edit_perm_nombre', 'edit_perm_descripcion'].forEach(id => {
      document.getElementById(id).classList.remove('is-invalid', 'is-valid');
    });

    new bootstrap.Modal(document.getElementById('editarPermisoModal')).show();
  } catch (err) {
    CevAlert.error({ title: 'Error', text: err.message, confirmButtonText: 'Aceptar' });
  }
};

const initEditPermiso = () => {
  const nombre = document.getElementById('edit_perm_nombre');
  const descripcion = document.getElementById('edit_perm_descripcion');
  if (!nombre) return;

  nombre.addEventListener('input', () => validarNombre(nombre, 3, 20));

  nombre.addEventListener('blur', async () => {
    if (validarNombre(nombre, 3, 20)) {
      const idExcluir = parseInt(document.getElementById('edit_perm_id').value);
      const val = nombre.value.trim();
      if (!val) return;
      try {
        const res = await apiClient.get(`a/configuracion/verificar_permiso?nombre=${encodeURIComponent(val)}&id_excluir=${idExcluir}`);
        if (res.existe) showError(nombre, 'Ya existe otro permiso con ese nombre.');
        else clearError(nombre);
      } catch {}
    }
  });

  descripcion.addEventListener('input', () => validarDescripcion(descripcion, 100));

  document.getElementById('btnGuardarEditarPermiso')?.addEventListener('click', async () => {
    const id = parseInt(document.getElementById('edit_perm_id').value);
    const nombreOk = validarNombre(nombre, 3, 20);
    const descOk = validarDescripcion(descripcion, 100);
    if (!nombreOk || !descOk) return;

    try {
      const res = await apiClient.get(`a/configuracion/verificar_permiso?nombre=${encodeURIComponent(nombre.value.trim())}&id_excluir=${id}`);
      if (res.existe) { showError(nombre, 'Ya existe otro permiso con ese nombre.'); return; }
    } catch {}

    try {
      await apiClient.post('a/configuracion/actualizar_permiso', {
        id, nombre: nombre.value.trim(), descripcion: descripcion.value.trim()
      });
      CevAlert.success({ title: 'Actualización Exitosa', text: 'Permiso actualizado correctamente.', confirmButtonText: 'Aceptar' });
      bootstrap.Modal.getInstance(document.getElementById('editarPermisoModal')).hide();
      dataTablePermisos.ajax.reload(null, false);
    } catch (err) {
      CevAlert.error({ title: 'Error', text: err.message, confirmButtonText: 'Aceptar' });
    }
  });
};

const ajustarColumnasDataTable = () => {
  if (window.$ && $.fn.dataTable) {
    $.fn.dataTable.tables({ api: true }).columns.adjust();
  }
};

// ==================== INIT ====================
const init = () => {
  initAdminShell();

  dataTableRoles = inicializarDataTable('#tablaRoles', '/a/configuracion/obtener_roles', columnsRoles);
  dataTableModulos = inicializarDataTable('#tablaModulos', '/a/configuracion/obtener_modulos', columnsModulos);
  dataTablePermisos = inicializarDataTable('#tablaPermisos', '/a/configuracion/obtener_permisos', columnsPermisos);

  initEditRol();
  initEditModulo();
  initEditPermiso();
};

document.querySelectorAll('#configTabs button[data-bs-toggle="tab"]').forEach(tab => {
  tab.addEventListener('shown.bs.tab', ajustarColumnasDataTable);
});

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', init);
} else {
  init();
}
