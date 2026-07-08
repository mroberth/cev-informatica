import { initAdminShell } from '/js/modules/admin/common.js';
import { apiClient } from '/js/api/client.js';
import { CevAlert } from '/js/utils/CevAlert.js';

export const initCrearEstudiante = () => {
  initAdminShell();
  const form = document.getElementById('formCrearEstudiante');
  if (!form) return;

  const elements = {
    id_usuario: form.id_usuario,
    tipo_cedula: form.tipo_cedula,
    cedula: form.cedula,
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

  cargarUsuariosEstudiantes();

  async function cargarUsuariosEstudiantes() {
    try {
      const response = await apiClient.get('a/estudiantes/obtener');
      const usuarios = response.data || [];

      elements.id_usuario.innerHTML = '<option value="">Seleccionar usuario</option>';
      usuarios.forEach(u => {
        const opt = document.createElement('option');
        opt.value = u.id;
        opt.textContent = `${u.nombre} ${u.apellido} - ${u.tipo_cedula}${u.cedula}`;
        opt.dataset.tipoCedula = u.tipo_cedula;
        opt.dataset.cedula = u.cedula;
        elements.id_usuario.appendChild(opt);
      });
    } catch (error) {
      console.error('Error al cargar usuarios:', error);
    }
  }

  function validarUsuario() {
    const val = elements.id_usuario.value;
    if (!val) {
      showError(elements.id_usuario, 'Debe seleccionar un usuario.');
      return false;
    }
    clearError(elements.id_usuario);
    return true;
  }

  elements.id_usuario.addEventListener('change', () => {
    if (validarUsuario()) {
      const selected = elements.id_usuario.selectedOptions[0];
      elements.tipo_cedula.value = selected.dataset.tipoCedula || 'V';
      elements.cedula.value = selected.dataset.cedula || '';
    } else {
      elements.tipo_cedula.value = 'V';
      elements.cedula.value = '';
    }
  });

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const validaciones = [validarUsuario()];

    if (validaciones.every(v => v === true)) {
      const btnSubmit = form.querySelector('[type="submit"]') || document.getElementById('btn-guardar');
      const originalText = btnSubmit.innerHTML;
      btnSubmit.disabled = true;
      btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Guardando...';

      try {
        const payload = {
          id_usuario: parseInt(elements.id_usuario.value),
        };

        await apiClient.post('a/estudiantes/registrar', payload);

        CevAlert.success({
          title: 'Registro Exitoso',
          text: 'Estudiante registrado correctamente.',
          confirmButtonText: 'Aceptar',
        });

        form.reset();
        form.querySelectorAll('.is-valid').forEach(el => el.classList.remove('is-valid'));
        elements.tipo_cedula.value = 'V';
        elements.cedula.value = '';
        cargarUsuariosEstudiantes();

      } catch (error) {
        CevAlert.error({
          title: 'Error al registrar',
          text: error.message || 'No se pudo guardar el estudiante.',
          confirmButtonText: 'Aceptar',
        });
      } finally {
        btnSubmit.disabled = false;
        btnSubmit.innerHTML = originalText;
      }
    } else {
      CevAlert.error({
        title: 'Error de Validación',
        text: 'Por favor, seleccione un usuario.',
        confirmButtonText: 'Aceptar',
      });
    }
  });
};

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initCrearEstudiante);
} else {
  initCrearEstudiante();
}
