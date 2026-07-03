import { apiClient } from '/js/api/client.js';
import { CevAlert } from '/js/utils/CevAlert.js';

const modal = document.getElementById('modalEditarUsuario');
let bootstrapModal = null;
let usuarioActual = null;

const elements = {
  id: document.getElementById('editar_id'),
  nombre: document.getElementById('editar_nombre'),
  apellido: document.getElementById('editar_apellido'),
  correo: document.getElementById('editar_correo'),
  password: document.getElementById('editar_password'),
  rol_id: document.getElementById('editar_rol_id'),
  estado: document.getElementById('editar_estado'),
};

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

const limpiarErrores = () => {
  Object.values(elements).forEach(el => {
    if (!el) return;
    el.classList.remove('is-invalid', 'is-valid');
    const errorEl = document.getElementById(`${el.id}Error`);
    if (errorEl) errorEl.textContent = '';
  });
};

const limpiarFormulario = () => {
  const form = document.getElementById('formEditarUsuario');
  if (form) form.reset();
  limpiarErrores();
};

const cargarRoles = async () => {
  if (!elements.rol_id) return;
  try {
    const response = await apiClient.get('a/usuarios/obtener_roles');
    const roles = response.data || response;
    elements.rol_id.innerHTML = '<option value="">Seleccionar rol</option>';
    roles.forEach(rol => {
      const option = document.createElement('option');
      option.value = rol.id || rol.rol_id;
      option.textContent = rol.nombre_rol || rol.rol;
      elements.rol_id.appendChild(option);
    });
  } catch (error) {
    console.error('Error al cargar roles:', error);
  }
};

const abrirModal = async (usuario) => {
  usuarioActual = usuario;
  limpiarFormulario();
  await cargarRoles();

  try {
    const response = await apiClient.get('a/usuarios/obtener_usuario?id=' + usuario.id);
    const data = response.data || response;

    elements.id.value = data.id;
    elements.nombre.value = data.nombre;
    elements.apellido.value = data.apellido;
    elements.correo.value = data.correo;
    elements.rol_id.value = data.rol_id;
    elements.estado.value = data.estado;

    Object.values(elements).forEach(el => {
      if (el && el.classList) {
        el.classList.remove('is-invalid', 'is-valid');
      }
    });

    if (!bootstrapModal) {
      bootstrapModal = new bootstrap.Modal(modal);
    }
    bootstrapModal.show();
  } catch (error) {
    CevAlert.error({
      title: 'Error',
      text: error.message || 'No se pudieron cargar los datos del usuario.',
    });
  }
};

function validarNombre() {
  const nombre = elements.nombre.value;
  const regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/;
  if (nombre.trim() === '') {
    showError(elements.nombre, 'El nombre es obligatorio.');
    return false;
  }
  if (nombre.length > 20) {
    showError(elements.nombre, 'El nombre ingresado excede los límites de longitud.');
    return false;
  }
  if (!regex.test(nombre)) {
    showError(elements.nombre, 'El nombre ingresado es inválido.');
    return false;
  }
  if (nombre.length < 3) {
    showError(elements.nombre, 'El nombre ingresado es demasiado corto.');
    return false;
  }
  clearError(elements.nombre);
  return true;
}

function validarApellido() {
  const apellido = elements.apellido.value;
  const regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/;
  if (apellido.trim() === '') {
    showError(elements.apellido, 'El apellido es obligatorio.');
    return false;
  }
  if (apellido.length > 20) {
    showError(elements.apellido, 'El apellido ingresado excede los límites de longitud.');
    return false;
  }
  if (!regex.test(apellido)) {
    showError(elements.apellido, 'El apellido ingresado es inválido.');
    return false;
  }
  if (apellido.length < 3) {
    showError(elements.apellido, 'El apellido ingresado es demasiado corto.');
    return false;
  }
  clearError(elements.apellido);
  return true;
}

async function validarCorreo() {
  const correo = elements.correo.value;
  const regex = /^[a-zA-Z0-9._%+-]+@(hotmail|yahoo|gmail|outlook)\.(com|es|net|org)$/i;
  if (correo.trim() === '') {
    showError(elements.correo, 'El correo es obligatorio.');
    return false;
  }
  if (correo.length > 30) {
    showError(elements.correo, 'El correo ingresado excede los límites de longitud.');
    return false;
  }
  if (!regex.test(correo)) {
    showError(elements.correo, 'El correo ingresado es inválido.');
    return false;
  }
  clearError(elements.correo);
  return true;
}

function validarPassword() {
  const password = elements.password.value;
  if (password === '') return true;
  const regex = /^(?=.*[#$%&.]).{8,}$/;
  if (!regex.test(password)) {
    showError(elements.password, 'La contraseña debe tener mínimo 8 caracteres y al menos un símbolo (#, $, %, &, .).');
    return false;
  }
  clearError(elements.password);
  return true;
}

function validarRol() {
  if (elements.rol_id.value === '') {
    showError(elements.rol_id, 'El rol del Usuario es obligatorio.');
    return false;
  }
  clearError(elements.rol_id);
  return true;
}

function validarEstado() {
  if (elements.estado.value === '') {
    showError(elements.estado, 'El estado del Usuario es obligatorio.');
    return false;
  }
  clearError(elements.estado);
  return true;
}

elements.nombre.addEventListener('input', validarNombre);
elements.apellido.addEventListener('input', validarApellido);
elements.correo.addEventListener('input', validarCorreo);
elements.password.addEventListener('input', validarPassword);
elements.rol_id.addEventListener('input', validarRol);
elements.estado.addEventListener('input', validarEstado);

document.getElementById('formEditarUsuario').addEventListener('submit', async (e) => {
  e.preventDefault();

  const validaciones = [
    validarNombre(),
    validarApellido(),
    await validarCorreo(),
    validarPassword(),
    validarRol(),
    validarEstado(),
  ];

  if (validaciones.every(v => v === true)) {
    const btnSubmit = modal.querySelector('button[type="submit"]');
    const originalText = btnSubmit.innerHTML;
    btnSubmit.disabled = true;
    btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Actualizando...';

    try {
      await apiClient.post('a/usuarios/actualizar_usuario', {
        id: parseInt(elements.id.value),
        nombre: elements.nombre.value.trim(),
        apellido: elements.apellido.value.trim(),
        correo: elements.correo.value.trim(),
        password: elements.password.value,
        rol_id: parseInt(elements.rol_id.value),
        estado: elements.estado.value,
      });

      CevAlert.success({
        title: 'Actualización Exitosa',
        text: 'Usuario actualizado correctamente.',
      });

      bootstrapModal.hide();
      limpiarFormulario();

      const event = new CustomEvent('cev:usuario-actualizado');
      document.dispatchEvent(event);
    } catch (error) {
      CevAlert.error({
        title: 'Error al actualizar',
        text: error.message || 'Ocurrió un error al actualizar el usuario.',
      });
    } finally {
      btnSubmit.disabled = false;
      btnSubmit.innerHTML = originalText;
    }
  } else {
    CevAlert.error({
      title: 'Error de Validación',
      text: 'Por favor, corrija los errores en el formulario antes de enviarlo.',
    });
  }
});

if (modal) {
  modal.addEventListener('hidden.bs.modal', limpiarFormulario);
}

export const initEditarUsuarios = (dataTableInstance) => {
  if (!dataTableInstance) return;

  document.getElementById('tablaUsuarios').addEventListener('click', (e) => {
    const btn = e.target.closest('.btn-outline-primary');
    if (!btn) return;

    const tr = btn.closest('tr');
    if (!tr) return;

    const rowData = dataTableInstance.row(tr).data();
    if (rowData) abrirModal(rowData);
  });
};
