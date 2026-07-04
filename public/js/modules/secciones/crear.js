import { initAdminShell } from '/js/modules/admin/common.js';
import { apiClient } from '/js/api/client.js';
import { CevAlert } from '/js/utils/CevAlert.js';

export const initCrearSeccion = () => {
  initAdminShell();
  const form = document.getElementById('formCrearSeccion');
  if (!form) return;

  // Carga inicial de selectores
  cargarPeriodos('id_periodo');
  cargarTrayectos('id_trayecto');

  const elements = {
    id_periodo: form.id_periodo,
    id_trayecto: form.id_trayecto,
    codigo_seccion: form.codigo_seccion,
    turno: form.turno,
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

  // ==========================================
  // VALIDADORES DEL FRONTEND 
  // ==========================================

  function validarPeriodo() {
    const val = elements.id_periodo.value;
    if (!val) {
      showError(elements.id_periodo, 'El período académico es obligatorio.');
      return false;
    }
    clearError(elements.id_periodo);
    return true;
  }

  function validarTrayecto() {
    const val = elements.id_trayecto.value;
    if (!val) {
      showError(elements.id_trayecto, 'El trayecto es obligatorio.');
      return false;
    }
    clearError(elements.id_trayecto);
    return true;
  }

  function validarCodigoSeccion() {
    const val = elements.codigo_seccion.value.trim().toUpperCase();
    elements.codigo_seccion.value = val; // Autocorregir a mayúsculas en el input

    if (!val) {
      showError(elements.codigo_seccion, 'El código de sección es obligatorio.');
      return false;
    }

    // Validación 1: Expresión regular para el formato general IN-X1YY
    // Donde X = Trayecto (1 a 4), el segundo dígito es un 1 fijo, y YY es la sección (01 a 99)
    const regex = /^IN-[1-4]1\d{2}$/;
    if (!regex.test(val)) {
      showError(elements.codigo_seccion, 'El formato debe ser IN-X1YY (ej: IN-1101, IN-2101).');
      return false;
    }

    // Validación 2: Coincidencia con el selector del Trayecto
    const idTrayectoSelected = elements.id_trayecto.value; // id del trayecto (1, 2, 3 o 4)
    if (idTrayectoSelected) {
      // El dígito del trayecto en el código está en la posición de índice 3: I-N-[-X]
      const digitoCodigo = val.charAt(3);
      if (digitoCodigo !== idTrayectoSelected) {
        showError(elements.codigo_seccion, `El código debe iniciar con IN-${idTrayectoSelected} para coincidir con el Trayecto seleccionado.`);
        return false;
      }
    }

    clearError(elements.codigo_seccion);
    return true;
  }

  function validarTurno() {
    const val = elements.turno.value;
    if (!val) {
      showError(elements.turno, 'El turno es obligatorio.');
      return false;
    }
    clearError(elements.turno);
    return true;
  }

  // Escuchadores de eventos en tiempo real
  elements.id_periodo.addEventListener('change', validarPeriodo);
  elements.id_trayecto.addEventListener('change', () => {
    validarTrayecto();
    const trayectoId = elements.id_trayecto.value;
    const currentCode = elements.codigo_seccion.value.trim();

    if (trayectoId && currentCode === '') {
      // Auto-completar el prefijo de la sección: IN-{trayecto}1
      elements.codigo_seccion.value = `IN-${trayectoId}1`;
      // Colocar el cursor al final para que complete el número de sección
      const len = elements.codigo_seccion.value.length;
      elements.codigo_seccion.setSelectionRange(len, len);
      elements.codigo_seccion.focus();
    } else if (currentCode !== '') {
      validarCodigoSeccion();
    }
  });
  elements.codigo_seccion.addEventListener('input', validarCodigoSeccion);
  elements.codigo_seccion.addEventListener('blur', async () => {
    const val = elements.codigo_seccion.value.trim().toUpperCase();
    if (!val) return;

    if (!validarCodigoSeccion()) return;

    try {
      const response = await apiClient.get(`a/secciones/verificar_codigo?codigo_seccion=${encodeURIComponent(val)}`);
      if (response.existe) {
        showError(elements.codigo_seccion, 'El código de sección ingresado ya existe.');
      }
    } catch (error) {
      console.error('Error al verificar código de sección:', error);
    }
  });
  elements.turno.addEventListener('change', validarTurno);

  // Manejador del envío del formulario
  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const validaciones = [
      validarPeriodo(),
      validarTrayecto(),
      validarCodigoSeccion(),
      validarTurno(),
    ];

    if (validaciones.every(v => v === true)) {
      const btnSubmit = form.querySelector('[type="submit"]') || document.getElementById('btn-guardar');
      const originalText = btnSubmit.innerHTML;
      btnSubmit.disabled = true;
      btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Guardando...';

      try {
        const payload = {
          id_periodo: parseInt(elements.id_periodo.value),
          id_trayecto: parseInt(elements.id_trayecto.value),
          codigo_seccion: elements.codigo_seccion.value.trim(),
          turno: elements.turno.value,
        };

        await apiClient.post('a/secciones/registrar', payload);

        CevAlert.success({
          title: 'Registro Exitoso',
          text: 'Sección registrada correctamente.',
          confirmButtonText: 'Aceptar',
        });

        form.reset();
        form.querySelectorAll('.is-valid').forEach(el => el.classList.remove('is-valid'));

      } catch (error) {
        CevAlert.error({
          title: 'Error al registrar',
          text: error.message || 'No se pudo guardar la sección.',
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

// ==========================================
// MÉTODOS AUXILIARES DE CARGA DE COMBOS (SELECTS)
// ==========================================

async function cargarPeriodos(selectId) {
  const select = document.getElementById(selectId);
  if (!select) return;

  try {
    const response = await apiClient.get('a/secciones/obtener_periodos');
    const periodos = response.data || [];
    
    select.innerHTML = '<option value="">Seleccionar período</option>';
    periodos.forEach(p => {
      const opt = document.createElement('option');
      opt.value = p.id;
      // Muestra si el período está activo para mejor UX
      const extra = p.estado === 'Activo' ? ' (Activo)' : '';
      opt.textContent = p.nombre + extra;
      select.appendChild(opt);
    });
  } catch (error) {
    console.error('Error al cargar períodos:', error);
  }
}

async function cargarTrayectos(selectId) {
  const select = document.getElementById(selectId);
  if (!select) return;

  try {
    const response = await apiClient.get('a/secciones/obtener_trayectos');
    const trayectos = response.data || [];
    
    select.innerHTML = '<option value="">Seleccionar trayecto</option>';
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
  document.addEventListener('DOMContentLoaded', initCrearSeccion);
} else {
  initCrearSeccion();
}
