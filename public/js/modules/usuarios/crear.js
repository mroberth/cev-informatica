import { initAdminShell } from '/js/modules/admin/common.js';
import { apiClient } from '/js/api/client.js';
import { CevAlert } from '/js/utils/CevAlert.js';

export const initCrearUsuarios = () => {
  initAdminShell();
  const form = document.getElementById('formCrearUsuario');
  if(!form) return;

  obtenerRoles();

  const elements = {
    tipo_cedula: form.tipo_cedula,
    cedula: form.cedula,
    nombre: form.nombre,
    apellido: form.apellido,
    correo: form.correo,
    telefono: form.telefono,
    password: form.password,
    rol_id: form.rol_id,
    estado: form.estado,
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

  function validarCedula() {
    const cedula = elements.cedula.value;
    const regex = /^\d+$/;

    if (cedula.trim() === '') {
      showError(elements.cedula, 'La cédula es obligatoria.');
      return false;
    }

    if (!regex.test(cedula)) {
      showError(elements.cedula, 'La cédula debe contener solo números.');
      return false;
    }

    if (cedula.length < 6 || cedula.length > 8) {
      showError(elements.cedula, 'La cédula debe tener entre 6 y 8 dígitos.');
      return false;
    }

    clearError(elements.cedula);
    return true;
  }

  async function verificarCedula() {
    if (!validarCedula()) return false;
    const tipo = elements.tipo_cedula.value;

    try {
      const response = await apiClient.get(`a/usuarios/verificar_cedula?tipo_cedula=${encodeURIComponent(tipo)}&cedula=${encodeURIComponent(elements.cedula.value)}`);
      if (response.existe) {
        showError(elements.cedula, 'La cédula ingresada ya se encuentra registrada.');
        return false;
      }
    } catch (error) {
      console.error('Error al verificar cédula:', error);
    }

    return true;
  }

  function validarNombre(){
    const nombre = elements.nombre.value;
    const regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/;

    if(nombre.trim() === ''){
      showError(elements.nombre, "El nombre es obligatorio.");
      return false;
    }

    if(nombre.length > 20){
      showError(elements.nombre, "El nombre ingresado excede los límites de longitud.");
      return false;
    }

    if(!regex.test(nombre)){
      showError(elements.nombre, "El nombre ingresado es inválido.");
      return false;
    }

    if(nombre.length < 3){
      showError(elements.nombre, "El nombre ingresado es demasiado corto.");
      return false;
    }

    clearError(elements.nombre);
    return true;
  }

  function validarApellido(){
    const apellido = elements.apellido.value;
    const regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/;

    if(apellido.trim() === ''){
      showError(elements.apellido, "El apellido es obligatorio.");
      return false;
    }

    if(apellido.length > 20){
      showError(elements.apellido, "El apellido ingresado excede los límites de longitud.");
      return false;
    }

    if(!regex.test(apellido)){
      showError(elements.apellido, "El apellido ingresado es inválido.");
      return false;
    }

    if(apellido.length < 3){
      showError(elements.apellido, "El apellido ingresado es demasiado corto.");
      return false;
    }

    clearError(elements.apellido);
    return true;
  }

  async function validarCorreo(){
    const correo = elements.correo.value;
    const regex = /^[a-zA-Z0-9._%+-]+@(hotmail|yahoo|gmail|outlook)\.(com|es|net|org)$/i;

    if(correo.trim() === ''){
      showError(elements.correo, "El correo es obligatorio.");
      return false;
    }

    if(correo.length > 30){
      showError(elements.correo, "El correo ingresado excede los límites de longitud.");
      return false;
    }

    if(!regex.test(correo)){
      showError(elements.correo, "El correo ingresado es inválido.");
      return false;
    }

    try {
      const response = await apiClient.get('a/usuarios/verificar_correo?correo=' + encodeURIComponent(correo));
      if (response.existe) {
        showError(elements.correo, "El correo ingresado ya se encuentra registrado.");
        return false;
      }
    } catch (error) {
      console.error('Error al verificar el correo:', error);
    }

    clearError(elements.correo);
    return true;
  }

  function validarTelefono() {
    const telefono = elements.telefono.value;
    const regex = /^\d+$/;
    const prefijos = ['0412', '0414', '0416', '0424', '0426', '0422'];

    if (telefono.trim() === '') {
      showError(elements.telefono, 'El teléfono es obligatorio.');
      return false;
    }

    if (!regex.test(telefono)) {
      showError(elements.telefono, 'El teléfono debe contener solo números.');
      return false;
    }

    if (telefono.length !== 11) {
      showError(elements.telefono, 'El teléfono debe tener 11 dígitos.');
      return false;
    }

    const prefijo = telefono.substring(0, 4);
    if (!prefijos.includes(prefijo)) {
      showError(elements.telefono, 'El prefijo del teléfono no es válido (0412, 0414, 0416, 0422, 0424, 0426).');
      return false;
    }

    clearError(elements.telefono);
    return true;
  }

  async function verificarTelefono() {
    if (!validarTelefono()) return false;

    try {
      const response = await apiClient.get(`a/usuarios/verificar_telefono?telefono=${encodeURIComponent(elements.telefono.value)}`);
      if (response.existe) {
        showError(elements.telefono, 'El teléfono ingresado ya se encuentra registrado.');
        return false;
      }
    } catch (error) {
      console.error('Error al verificar teléfono:', error);
    }

    return true;
  }

  function validarPassword() {
    const password = elements.password.value;
    const regex = /^(?=.*[#$%&.]).{8,}$/;

    if (password === '') {
        showError(elements.password, "La contraseña es obligatoria.");
        return false;
    }

    if (!regex.test(password)) {
        showError(elements.password, "La contraseña debe tener mínimo 8 caracteres y al menos un símbolo (#, $, %, &, .).");
        return false;
    }

    clearError(elements.password);
    return true;
  }

  function validarRol(){
    const idRol = elements.rol_id.value;

    if(idRol === ''){
      showError(elements.rol_id, "El rol del Usuario es obligatorio.");
      return false;
    }

    clearError(elements.rol_id);
    return true;
  }

  function validarEstado(){
    const estado = elements.estado.value;

    if(estado === ''){
      showError(elements.estado, "El estado del Usuario es obligatorio.");
      return false;
    }

    clearError(elements.estado);
    return true;
  }

  elements.cedula.addEventListener('input', validarCedula);
  elements.cedula.addEventListener('blur', verificarCedula);
  elements.nombre.addEventListener('input', validarNombre);
  elements.apellido.addEventListener('input', validarApellido);
  elements.correo.addEventListener('blur', validarCorreo);
  elements.telefono.addEventListener('input', validarTelefono);
  elements.telefono.addEventListener('blur', verificarTelefono);
  elements.password.addEventListener('input', validarPassword);
  elements.rol_id.addEventListener('input', validarRol);
  elements.estado.addEventListener('input', validarEstado);

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const validaciones = [
      validarCedula(),
      validarNombre(),
      validarApellido(),
      await validarCorreo(),
      validarTelefono(),
      validarPassword(),
      validarRol(),
      validarEstado()
    ];

    if(validaciones.every(v => v === true)){
      const cedulaOk = await verificarCedula();
      const telefonoOk = await verificarTelefono();
      if (!cedulaOk || !telefonoOk) return;

      const btnSubmit = form.querySelector('[type="submit"]') || document.getElementById('btn-guardar');
      const originalBtnText = btnSubmit.innerHTML;

      btnSubmit.disabled = true;
      btnSubmit.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span> Guardando...`;

      try{
        const payload = {
          tipo_cedula: form.elements.tipo_cedula.value,
          cedula: form.elements.cedula.value.trim(),
          nombre: form.elements.nombre.value.trim(),
          apellido: form.elements.apellido.value.trim(),
          correo: form.elements.correo.value.trim(),
          telefono: form.elements.telefono.value.trim(),
          password: form.elements.password.value,
          rol_id: parseInt(form.elements.rol_id.value),
          estado: form.elements.estado.value
        };

        const response = await apiClient.post('a/usuarios/registrar_usuarios', payload);

        CevAlert.success({
          title: 'Registro Exitoso',
          text: 'El usuario ha sido registrado exitosamente.',
          confirmButtonText: "Aceptar"
        });
        form.reset();
        form.querySelectorAll('.is-valid').forEach(el => el.classList.remove('is-valid'));
      } catch(error){
        CevAlert.error({
          title: "Error al guardar",
          text: error.message || "Ocurrió un error inesperado al intentar guardar el usuario.",
          confirmButtonText: "Aceptar"
        });
      } finally{
        btnSubmit.disabled = false;
        btnSubmit.innerHTML = originalBtnText;
      }

    } else{
      CevAlert.error({
        title: "Error de Validación",
        text: "Por favor, corrija los errores en el formulario antes de enviarlo.",
        confirmButtonText: "Aceptar"
      });
    }
  });
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initCrearUsuarios);
} else {
  initCrearUsuarios();
}

async function obtenerRoles() {
  const select = document.getElementById('rol_id');
  if (!select) return;

  try {
    const response = await apiClient.get('a/usuarios/obtener_roles');
    const roles = response.data || response;

    select.innerHTML = '<option value="">Seleccionar rol</option>';

    roles.forEach(rol => {
      const option = document.createElement('option');
      option.value = rol.id || rol.rol_id;
      option.textContent = rol.nombre_rol || rol.rol;
      select.appendChild(option);
    });
  } catch (error) {
    console.error('Error al cargar roles:', error);
  }
}
