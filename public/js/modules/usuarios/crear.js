import { initAdminShell } from '/js/modules/admin/common.js';
import { apiClient } from '/js/api/client.js';
import { CevAlert } from '/js/utils/CevAlert.js';

function obtenerElementoForm() {
  return document.getElementById('formCrearUsuario');
}

function setError(field, message) {
  const errorElement = document.getElementById(`${field.id}Error`);
  if (errorElement) {
    errorElement.textContent = message;
  }
  field.classList.add('is-invalid');
  field.classList.remove('is-valid');
}

function clearError(field) {
  const errorElement = document.getElementById(`${field.id}Error`);
  if (errorElement) {
    errorElement.textContent = '';
  }
  field.classList.remove('is-invalid');
  field.classList.add('is-valid');
}

function validarNombre(field) {
  if (field.value.trim() === '') {
    setError(field, 'El nombre es obligatorio.');
    return false;
  }

  if (field.value.trim().length < 2) {
    setError(field, 'El nombre debe tener al menos 2 caracteres.');
    return false;
  }

  clearError(field);
  return true;
}

function validarApellido(field) {
  if (field.value.trim() === '') {
    setError(field, 'El apellido es obligatorio.');
    return false;
  }

  if (field.value.trim().length < 2) {
    setError(field, 'El apellido debe tener al menos 2 caracteres.');
    return false;
  }

  clearError(field);
  return true;
}

function validarCorreo(field) {
  const regex = /^[a-zA-Z0-9._%+-]+@(hotmail|yahoo|gmail|outlook)\.(com|es|net|org)$/i;

  if (field.value.trim() === '') {
    setError(field, 'El correo es obligatorio.');
    return false;
  }

  if (!regex.test(field.value.trim())) {
    setError(field, 'El correo ingresado es inválido.');
    return false;
  }

  clearError(field);
  return true;
}

function validarPassword(field) {
  if (field.value === '') {
    setError(field, 'La contraseña es obligatoria.');
    return false;
  }

  if (field.value.length < 5) {
    setError(field, 'La contraseña debe tener al menos 5 caracteres.');
    return false;
  }

  clearError(field);
  return true;
}

function validarSelect(field, mensaje) {
  if (field.value === '') {
    setError(field, mensaje);
    return false;
  }

  clearError(field);
  return true;
}

function inicializarValidaciones(form) {
  const campos = {
    nombre: form.nombre,
    apellido: form.apellido,
    correo: form.correo,
    password: form.password,
    rol_id: form.rol_id,
    estado: form.estado,
  };

  campos.nombre.addEventListener('input', () => validarNombre(campos.nombre));
  campos.apellido.addEventListener('input', () => validarApellido(campos.apellido));
  campos.correo.addEventListener('input', () => validarCorreo(campos.correo));
  campos.password.addEventListener('input', () => validarPassword(campos.password));
  campos.rol_id.addEventListener('change', () => validarSelect(campos.rol_id, 'Selecciona un rol.'));
  campos.estado.addEventListener('change', () => validarSelect(campos.estado, 'Selecciona un estado.'));

  return campos;
}

async function enviarFormulario(form, campos) {
  const endpointGuardado = window.CEV_USUARIOS_GUARDAR_ENDPOINT || '';

  const payload = {
    nombre: campos.nombre.value.trim(),
    apellido: campos.apellido.value.trim(),
    correo: campos.correo.value.trim(),
    password: campos.password.value,
    rol_id: campos.rol_id.value,
    estado: campos.estado.value,
  };

  if (!endpointGuardado) {
    CevAlert.info({
      title: 'Formulario listo',
      text: 'Las validaciones están activas. Falta conectar el endpoint backend para guardar usuarios.',
      confirmButtonText: 'Entendido',
    });
    console.info('Payload de crear usuario listo para enviar:', payload);
    return;
  }

  const response = await apiClient.post(endpointGuardado, payload);
  const mensaje = response?.data?.message || 'Usuario creado correctamente.';

  CevAlert.success({
    title: 'Guardado',
    text: mensaje,
  });

  form.reset();
  Object.values(campos).forEach((campo) => {
    campo.classList.remove('is-valid', 'is-invalid');
  });
}

function inicializarFormularioCrearUsuarios() {
  initAdminShell();

  const form = obtenerElementoForm();
  if (!form) {
    return;
  }

  const campos = inicializarValidaciones(form);

  form.addEventListener('submit', async (event) => {
    event.preventDefault();

    const esValido = [
      validarNombre(campos.nombre),
      validarApellido(campos.apellido),
      validarCorreo(campos.correo),
      validarPassword(campos.password),
      validarSelect(campos.rol_id, 'Selecciona un rol.'),
      validarSelect(campos.estado, 'Selecciona un estado.'),
    ].every(Boolean);

    if (!esValido) {
      CevAlert.warning({
        title: 'Formulario incompleto',
        text: 'Corrige los campos resaltados antes de continuar.',
      });
      return;
    }

    try {
      await enviarFormulario(form, campos);
    } catch (error) {
      CevAlert.error({
        title: 'Error al guardar',
        text: error.message || 'No fue posible guardar el usuario.',
      });
    }
  });
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', inicializarFormularioCrearUsuarios);
} else {
  inicializarFormularioCrearUsuarios();
}
