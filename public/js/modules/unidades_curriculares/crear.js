import { initAdminShell } from '/js/modules/admin/common.js';
import { apiClient } from '/js/api/client.js';
import { CevAlert } from '/js/utils/CevAlert.js';

export const initCrearUC = () => {
  initAdminShell();
  const form = document.getElementById('formCrearUC');
  if (!form) return;

  cargarTrayectos('id_trayecto');

  const idTrayecto = document.getElementById('id_trayecto');
  const idFase = document.getElementById('id_fase');

  idTrayecto.addEventListener('change', async () => {
    const val = idTrayecto.value;
    idFase.innerHTML = '<option value="">Cargando...</option>';
    if (!val) {
      idFase.innerHTML = '<option value="">Primero selecciona un trayecto</option>';
      return;
    }
    try {
      const response = await apiClient.get(`a/unidades-curriculares/obtener_fases?id_trayecto=${val}`);
      const fases = response.data || [];
      idFase.innerHTML = '<option value="">Seleccionar fase</option>';
      fases.forEach(f => {
        const opt = document.createElement('option');
        opt.value = f.id;
        opt.textContent = f.nombre;
        idFase.appendChild(opt);
      });
    } catch {
      idFase.innerHTML = '<option value="">Error al cargar fases</option>';
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

  function validarFase() {
    if (!idFase.value) {
      showError(idFase, 'Debes seleccionar una fase.');
      return false;
    }
    clearError(idFase);
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
      validarFase(),
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
      const payload = {
        id_fase: parseInt(idFase.value),
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
      idFase.innerHTML = '<option value="">Primero selecciona un trayecto</option>';
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
