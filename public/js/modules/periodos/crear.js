import { initAdminShell } from '/js/modules/admin/common.js';
import { apiClient } from '/js/api/client.js';
import { CevAlert } from '/js/utils/CevAlert.js';

export const initCrearPeriodo = () => {
  initAdminShell();
  const form = document.getElementById('formCrearPeriodo');
  if (!form) return;

  const elements = {
    nombre: form.nombre,
    fecha_inicio: form.fecha_inicio,
    fecha_fin: form.fecha_fin,
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

  const NOMBRE_REGEX = /^\d{4}-(I|II)$/i;

  function validarNombre() {
    let val = elements.nombre.value.trim().toUpperCase();
    elements.nombre.value = val;

    if (!val) {
      showError(elements.nombre, 'El nombre del período es obligatorio.');
      return false;
    }

    if (!NOMBRE_REGEX.test(val)) {
      showError(elements.nombre, 'El formato debe ser AÑO-Semestre (ej: 2026-I, 2026-II).');
      return false;
    }

    clearError(elements.nombre);
    return true;
  }

  function validarFechaInicio() {
    const val = elements.fecha_inicio.value;
    if (!val) {
      showError(elements.fecha_inicio, 'La fecha de inicio es obligatoria.');
      return false;
    }
    clearError(elements.fecha_inicio);
    return true;
  }

  function validarFechaFin() {
    const val = elements.fecha_fin.value;
    if (!val) {
      showError(elements.fecha_fin, 'La fecha de fin es obligatoria.');
      return false;
    }

    const inicio = elements.fecha_inicio.value;
    if (inicio && val <= inicio) {
      showError(elements.fecha_fin, 'La fecha de fin debe ser posterior a la fecha de inicio.');
      return false;
    }

    clearError(elements.fecha_fin);
    return true;
  }

  function validarEstado() {
    const val = elements.estado.value;
    if (!val) {
      showError(elements.estado, 'El estado es obligatorio.');
      return false;
    }
    clearError(elements.estado);
    return true;
  }

  elements.nombre.addEventListener('input', validarNombre);
  elements.fecha_inicio.addEventListener('change', () => {
    validarFechaInicio();
    if (elements.fecha_fin.value) validarFechaFin();
  });
  elements.fecha_fin.addEventListener('change', validarFechaFin);
  elements.estado.addEventListener('change', validarEstado);

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const validaciones = [
      validarNombre(),
      validarFechaInicio(),
      validarFechaFin(),
      validarEstado(),
    ];

    if (validaciones.every(v => v === true)) {
      const btnSubmit = form.querySelector('[type="submit"]') || document.getElementById('btn-guardar');
      const originalText = btnSubmit.innerHTML;
      btnSubmit.disabled = true;
      btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Guardando...';

      try {
        const payload = {
          nombre: elements.nombre.value.trim(),
          fecha_inicio: elements.fecha_inicio.value,
          fecha_fin: elements.fecha_fin.value,
          estado: elements.estado.value,
        };

        await apiClient.post('a/periodos/registrar', payload);

        CevAlert.success({
          title: 'Registro Exitoso',
          text: 'Período académico registrado correctamente.',
          confirmButtonText: 'Aceptar',
        });

        form.reset();
        form.querySelectorAll('.is-valid').forEach(el => el.classList.remove('is-valid'));

      } catch (error) {
        CevAlert.error({
          title: 'Error al registrar',
          text: error.message || 'No se pudo guardar el período.',
          confirmButtonText: 'Aceptar',
        });
      } finally {
        btnSubmit.disabled = false;
        btnSubmit.innerHTML = originalText;
      }
    } else {
      CevAlert.error({
        title: 'Error de Validación',
        text: 'Por favor, corrija los errores del formulario.',
        confirmButtonText: 'Aceptar',
      });
    }
  });
};

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initCrearPeriodo);
} else {
  initCrearPeriodo();
}
