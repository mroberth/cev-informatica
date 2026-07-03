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

const verificarDuplicado = async (endpoint, field, idExcluir = null) => {
  const val = field.value.trim();
  if (!val) return false;
  try {
    let url = `${endpoint}?nombre=${encodeURIComponent(val)}`;
    if (idExcluir !== null) url += `&id_excluir=${idExcluir}`;
    const res = await apiClient.get(url);
    if (res.existe) {
      showError(field, 'Ya existe un registro con ese nombre.');
      return false;
    }
    return true;
  } catch {
    return true;
  }
};

const initForm = (formId, nombreId, descripcionId, endpoint, nombreLabel, minLen, maxLen) => {
  const form = document.getElementById(formId);
  if (!form) return;

  const nombre = document.getElementById(nombreId);
  const descripcion = document.getElementById(descripcionId);

  nombre.addEventListener('input', () => validarNombre(nombre, minLen, maxLen));

  nombre.addEventListener('blur', async () => {
    if (validarNombre(nombre, minLen, maxLen)) {
      await verificarDuplicado(endpoint, nombre);
    }
  });

  descripcion.addEventListener('input', () => validarDescripcion(descripcion, 255));

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const nombreOk = validarNombre(nombre, minLen, maxLen);
    const descOk = validarDescripcion(descripcion, 255);

    if (!nombreOk || !descOk) return;

    const duplicado = await verificarDuplicado(endpoint, nombre);
    if (!duplicado) return;

    try {
      await apiClient.post(endpoint.replace('verificar', 'guardar'), {
        [`${nombreId.includes('rol') ? 'nombre_rol' : 'nombre'}`]: nombre.value.trim(),
        descripcion: descripcion.value.trim(),
      });
      CevAlert.success({
        title: 'Registro Exitoso',
        text: `${nombreLabel} creado correctamente.`,
        confirmButtonText: 'Aceptar'
      });
      form.reset();
      form.querySelectorAll('.is-valid').forEach(el => el.classList.remove('is-valid'));
    } catch (err) {
      CevAlert.error({
        title: 'Error al guardar',
        text: err.message,
        confirmButtonText: 'Aceptar'
      });
    }
  });
};

const init = () => {
  initAdminShell();

  initForm('formRol', 'rol_nombre', 'rol_descripcion',
    'a/configuracion/verificar_rol', 'Rol', 3, 50);

  initForm('formModulo', 'mod_nombre', 'mod_descripcion',
    'a/configuracion/verificar_modulo', 'Módulo', 3, 50);

  initForm('formPermiso', 'perm_nombre', 'perm_descripcion',
    'a/configuracion/verificar_permiso', 'Permiso', 3, 20);
};

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', init);
} else {
  init();
}
