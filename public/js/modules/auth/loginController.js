import { apiClient } from '/js/api/client.js';
import { CevAlert } from '/js/utils/CevAlert.js'; // <-- Ruta absoluta desde la raíz web

const mensajesRedireccion = {
  no_auth: { title: 'Acceso denegado', text: 'Debes iniciar sesión primero para acceder a esta sección.' },
  expired: { title: 'Sesión expirada', text: 'Tu sesión ha expirado. Inicia sesión nuevamente.' },
};

export function mostrarMotivoRedireccion(reason) {
  const msg = mensajesRedireccion[reason];
  if (msg) {
    CevAlert.warning({ title: msg.title, text: msg.text });
  }
}

export const initLogin = () => {
  const form = document.getElementById('form-login');
  const btn = document.getElementById('btn-login'); // Traemos el botón para manejar el estado de carga (UX)
  if (!form) return;

  const elements = {
    correo: form.correo,
    password: form.password,
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

  function validarCorreo() {
    const correo = elements.correo.value;
    const regex = /^[a-zA-Z0-9._%+-]+@(hotmail|yahoo|gmail|outlook)\.(com|es|net|org)$/i;

    if (correo.trim() === '') {
      showError(elements.correo, "El correo es obligatorio.");
      return false;
    }
    if (!regex.test(correo)) {
      showError(elements.correo, "El correo ingresado es inválido.");
      return false;
    }
    if (correo.length > 30) {
      showError(elements.correo, "El correo ingresado excede los límites de longitud.");
      return false;
    }

    clearError(elements.correo);
    return true;
  }

  function validarPassword() {
    const password = elements.password.value;
    
    if (password === '') {
      showError(elements.password, "La contraseña es obligatoria.");
      return false;
    }
    if (password.length > 100) {
      showError(elements.password, "La contraseña excede los límites de longitud.");
      return false;
    }
    if (password.length < 5) {
      showError(elements.password, "La contraseña debe tener al menos 5 caracteres.");
      return false;
    }

    clearError(elements.password);
    return true;
  }

  elements.correo.addEventListener('input', validarCorreo);
  elements.password.addEventListener('input', validarPassword);
  togglePasswordVisibility();

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    // Ejecutamos ambas validaciones
    const esCorreoValido = validarCorreo();
    const esPasswordValido = validarPassword();

    if (esCorreoValido && esPasswordValido) {
      
      // 1. Manejo de UX: Estado de carga del botón
      const originalBtnText = btn.innerHTML;
      btn.disabled = true;
      btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span> Validando...`;

      try {
        // 2. Extraer los datos y convertirlos a Objeto Plano para enviarse como JSON válido
        const payload = {
          correo: elements.correo.value.trim(),
          password: elements.password.value
        };

        // 3. Disparar a través de tu cliente blindado (asumiendo que tu ruta de login es 'auth/login')
        const response = await apiClient.post('auth/login', payload);

        // 4. Guardar la sesión JWT de forma local para las siguientes peticiones del cliente
        const user = response.data.user;
        localStorage.setItem('token', response.data.access_token);
        localStorage.setItem('refresh_token', response.data.refresh_token);
        localStorage.setItem('user_rol', user.rol || user.nombre_rol);

        CevAlert.success({
          title: '¡Bienvenido!',
          text: 'Redirigiendo al sistema...',
          timer: 1500,
          showConfirmButton: false
        });

        // 5. Redirección basada en roles controlada por el frontend
        setTimeout(() => {
          window.location.href = user.rol === 'Admin' ? '/a/dashboard' : '/u/dashboard';
        }, 1500);

      } catch (error) {
        // 6. Si algo falla, el apiClient arrojará un error con el mensaje limpio del servidor
        btn.disabled = false;
        btn.innerHTML = originalBtnText;

        CevAlert.error({
          title: "Error de acceso",
          text: error.message || "Credenciales incorrectas."
        });
      }
    } else {
      CevAlert.warning({
        title: "Formulario incompleto",
        text: "Corrige los campos resaltados antes de continuar."
      });
    }
  });
};