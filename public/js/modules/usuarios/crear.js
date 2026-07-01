import { initAdminShell } from '/js/modules/admin/common.js';
import { apiClient } from '/js/api/client.js';
import { CevAlert } from '/js/utils/CevAlert.js';

export const initCrearUsuarios = () => {
  initAdminShell();
  const form = document.getElementById('formCrearUsuario');
  if(!form) return;

  obtenerRoles();

  const elements = {
    nombre: form.nombre,
    apellido: form.apellido,
    correo: form.correo,
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

  const togglePasswordVisibility = () => {
    const toggleButton = document.getElementById('togglePassword');
    const toggleIcon = document.getElementById('togglePasswordIcon');
    if (!toggleButton || !toggleIcon) return;

    toggleButton.addEventListener('click', () => {
      const currentType = elements.password.type;
      const nextType = currentType === 'password' ? 'text' : 'password';
      elements.password.type = nextType;
      toggleIcon.classList.toggle('bi-eye', nextType === 'text');
      toggleIcon.classList.toggle('bi-eye-slash', nextType === 'password');
    });
  };

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

    //Validacion en tiempo real en la base de datos
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

  elements.nombre.addEventListener('input', validarNombre);
  elements.apellido.addEventListener('input', validarApellido);
  elements.correo.addEventListener('blur', validarCorreo);
  elements.password.addEventListener('input', validarPassword);
  elements.rol_id.addEventListener('input', validarRol);
  elements.estado.addEventListener('input', validarEstado);

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const validaciones = [
      validarNombre(),
      validarApellido(),
      await validarCorreo(),
      validarPassword(),
      validarRol(),
      validarEstado()
    ];

    if(validaciones.every(v => v === true)){
      const btnSubmit = form.querySelector('[type="submit"]') || document.getElementById('btn-guardar');
      const originalBtnText = btnSubmit.innerHTML;

      btnSubmit.disabled = true;
      btnSubmit.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span> Guardando...`;

      try{
        const payload = {
          nombre: form.elements.nombre.value.trim(),
          apellido: form.elements.apellido.value.trim(),
          correo: form.elements.correo.value.trim(),
          password: form.elements.password.value,
          rol_id: parseInt(form.elements.rol_id.value),
          estado: form.elements.estado.value
        };

        const response = await apiClient.post('a/usuarios/registrar_usuarios', payload);

        CevAlert.success({
          title: "Registro Exitoso",
          text: "El usuario ha sido registrado exitosamente.",
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
