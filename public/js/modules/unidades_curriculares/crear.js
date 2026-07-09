import { initAdminShell } from '/js/modules/admin/common.js';
import { apiClient } from '/js/api/client.js';
import { CevAlert } from '/js/utils/CevAlert.js';

export const initCrearUC = () => {
  initAdminShell();
  const form = document.getElementById('formCrearUC');
  if (!form) return;

  cargarTrayectos('id_trayecto');

  const idTrayecto = document.getElementById('id_trayecto');
  const fasesContainer = document.getElementById('fases_container');

  const fasesCheckboxes = new Map();

  idTrayecto.addEventListener('change', async () => {
    const val = idTrayecto.value;
    fasesCheckboxes.clear();
    fasesContainer.innerHTML = '<span class="text-muted small">Cargando...</span>';
    if (!val) {
      fasesContainer.innerHTML = '<span class="text-muted small">Primero selecciona un trayecto</span>';
      return;
    }
    try {
      const response = await apiClient.get(`a/unidades-curriculares/obtener_fases?id_trayecto=${val}`);
      const fases = response.data || [];
      if (fases.length === 0) {
        fasesContainer.innerHTML = '<span class="text-muted small">No hay fases para este trayecto</span>';
        return;
      }
      fasesContainer.innerHTML = '';
      fases.forEach(f => {
        const div = document.createElement('div');
        div.className = 'form-check form-check-inline';
        const input = document.createElement('input');
        input.className = 'form-check-input';
        input.type = 'checkbox';
        input.id = `fase_${f.id}`;
        input.value = f.id;
        input.name = 'fases[]';
        const label = document.createElement('label');
        label.className = 'form-check-label';
        label.htmlFor = `fase_${f.id}`;
        label.textContent = f.nombre;
        div.appendChild(input);
        div.appendChild(label);
        fasesContainer.appendChild(div);
        fasesCheckboxes.set(f.id, input);
      });
    } catch {
      fasesContainer.innerHTML = '<span class="text-muted small">Error al cargar fases</span>';
    }
  });

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

  function validarTrayecto() {
    if (!idTrayecto.value) {
      showError(idTrayecto, 'Debes seleccionar un trayecto.');
      return false;
    }
    clearError(idTrayecto);
    return true;
  }

  function validarFases() {
    const checked = [...fasesCheckboxes.values()].some(cb => cb.checked);
    const errorEl = document.getElementById('fasesError');
    if (!checked) {
      if (errorEl) errorEl.textContent = 'Debes seleccionar al menos una fase.';
      fasesContainer.classList.add('is-invalid');
      fasesContainer.classList.remove('is-valid');
      return false;
    }
    if (errorEl) errorEl.textContent = '';
    fasesContainer.classList.remove('is-invalid');
    fasesContainer.classList.add('is-valid');
    return true;
  }

  const codigoInput = document.getElementById('codigo');
  const nombreInput = document.getElementById('nombre');
  const ucInput = document.getElementById('unidades_credito');

  function validarCodigo() {
    const val = codigoInput.value.trim();
    if (!val) {
      showError(codigoInput, 'El código es obligatorio.');
      return false;
    }
    if (val.length > 20) {
      showError(codigoInput, 'El código no debe exceder 20 caracteres.');
      return false;
    }
    if (!/^[A-Za-z0-9\-]+$/.test(val)) {
      showError(codigoInput, 'El código contiene caracteres no válidos.');
      return false;
    }
    clearError(codigoInput);
    return true;
  }

  async function verificarCodigo() {
    if (!validarCodigo()) return false;
    try {
      const response = await apiClient.get(`a/unidades-curriculares/verificar_codigo?codigo=${encodeURIComponent(codigoInput.value.trim())}`);
      if (response.existe) {
        showError(codigoInput, 'El código ingresado ya existe.');
        return false;
      }
    } catch { /* ignore */ }
    return true;
  }

  function validarNombre() {
    const val = nombreInput.value.trim();
    if (!val) {
      showError(nombreInput, 'El nombre es obligatorio.');
      return false;
    }
    if (val.length > 100) {
      showError(nombreInput, 'El nombre no debe exceder 100 caracteres.');
      return false;
    }
    if (val.length < 3) {
      showError(nombreInput, 'El nombre debe tener al menos 3 caracteres.');
      return false;
    }
    clearError(nombreInput);
    return true;
  }

  function validarUC() {
    const val = parseInt(ucInput.value);
    if (!val || val <= 0) {
      showError(ucInput, 'Las unidades de crédito deben ser un valor positivo.');
      return false;
    }
    if (val > 20) {
      showError(ucInput, 'Las unidades de crédito no pueden exceder 20.');
      return false;
    }
    clearError(ucInput);
    return true;
  }

  codigoInput.addEventListener('input', validarCodigo);
  codigoInput.addEventListener('blur', verificarCodigo);
  nombreInput.addEventListener('input', validarNombre);
  ucInput.addEventListener('input', validarUC);

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const validaciones = [
      validarTrayecto(),
      validarFases(),
      validarCodigo(),
      validarNombre(),
      validarUC(),
    ];

    if (!validaciones.every(v => v === true)) return;

    const codigoOk = await verificarCodigo();
    if (!codigoOk) return;

    const btnSubmit = form.querySelector('[type="submit"]') || document.getElementById('btn-guardar');
    const originalText = btnSubmit.innerHTML;
    btnSubmit.disabled = true;
    btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Guardando...';

    try {
      const fasesSeleccionadas = [...fasesCheckboxes.values()]
        .filter(cb => cb.checked)
        .map(cb => parseInt(cb.value));

      const payload = {
        fases: fasesSeleccionadas,
        codigo: codigoInput.value.trim(),
        nombre: nombreInput.value.trim(),
        unidades_credito: parseInt(ucInput.value),
      };

      await apiClient.post('a/unidades-curriculares/registrar', payload);

      CevAlert.success({
        title: 'Registro Exitoso',
        text: 'La unidad curricular ha sido registrada exitosamente.',
        confirmButtonText: 'Aceptar',
      });
      form.reset();
      form.querySelectorAll('.is-valid').forEach(el => el.classList.remove('is-valid'));
      fasesContainer.innerHTML = '<span class="text-muted small">Primero selecciona un trayecto</span>';
      fasesContainer.classList.remove('is-invalid', 'is-valid');
    } catch (error) {
      CevAlert.error({
        title: 'Error al guardar',
        text: error.message || 'Ocurrió un error inesperado.',
        confirmButtonText: 'Aceptar',
      });
    } finally {
      btnSubmit.disabled = false;
      btnSubmit.innerHTML = originalText;
    }
  });
};

async function cargarTrayectos(selectId) {
  const select = document.getElementById(selectId);
  if (!select) return;
  try {
    const response = await apiClient.get('a/unidades-curriculares/obtener_trayectos');
    const trayectos = response.data || [];
    trayectos.forEach(t => {
      const opt = document.createElement('option');
      opt.value = t.id;
      opt.textContent = t.nombre;
      select.appendChild(opt);
    });
  } catch (error) {
    console.error('Error al cargar trayectos:', error);
  }
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initCrearUC);
} else {
  initCrearUC();
}
