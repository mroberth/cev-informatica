import { initAdminShell } from '/js/modules/admin/common.js';
import { apiClient } from '/js/api/client.js';
import { CevAlert } from '/js/utils/CevAlert.js';

export const initCrearDocente = () => {
  initAdminShell();
  const form = document.getElementById('formCrearDocente');
  if (!form) return;

  const elements = {
    id_usuario: form.id_usuario,
    tipo_cedula: form.tipo_cedula,
    cedula: form.cedula,
    especialidad: form.especialidad,
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

  cargarUsuariosDocentes();

  async function cargarUsuariosDocentes() {
    try {
      const response = await apiClient.get('a/docentes/obtener');
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

  function validarEspecialidad() {
    const val = elements.especialidad.value.trim();
    const regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;
    if (!val) {
      showError(elements.especialidad, 'La especialidad es obligatoria.');
      return false;
    }
    
    if (!regex.test(val)) {
      showError(elements.especialidad, 'La especialidad solo puede contener letras y espacios.');
      return false;
    }

    if (val.length > 30) {
      showError(elements.especialidad, 'La especialidad no puede exceder 30 caracteres.');
      return false;
    }
    clearError(elements.especialidad);
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

  elements.especialidad.addEventListener('input', validarEspecialidad);

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const validaciones = [
      validarUsuario(),
      validarEspecialidad(),
    ];

    if (validaciones.every(v => v === true)) {
      const btnSubmit = form.querySelector('[type="submit"]') || document.getElementById('btn-guardar');
      const originalText = btnSubmit.innerHTML;
      btnSubmit.disabled = true;
      btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Guardando...';

      try {
        const payload = {
          id_usuario: parseInt(elements.id_usuario.value),
          tipo_cedula: elements.tipo_cedula.value,
          cedula: elements.cedula.value.trim(),
          especialidad: elements.especialidad.value.trim(),
        };

        await apiClient.post('a/docentes/registrar', payload);

        CevAlert.success({
          title: 'Registro Exitoso',
          text: 'Docente registrado correctamente.',
          confirmButtonText: 'Aceptar',
        });

        form.reset();
        form.querySelectorAll('.is-valid').forEach(el => el.classList.remove('is-valid'));
        elements.tipo_cedula.value = 'V';
        elements.cedula.value = '';
        cargarUsuariosDocentes();

      } catch (error) {
        CevAlert.error({
          title: 'Error al registrar',
          text: error.message || 'No se pudo guardar el docente.',
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
  document.addEventListener('DOMContentLoaded', initCrearDocente);
} else {
  initCrearDocente();
}
