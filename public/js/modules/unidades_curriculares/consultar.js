import { initAdminShell } from '/js/modules/admin/common.js';
import { apiClient } from '/js/api/client.js';
import { CevAlert } from '/js/utils/CevAlert.js';

let dataTable = null;

const inicializarDataTable = () => {
  dataTable = new DataTable('#tablaUC', {
    ajax: {
      url: '/a/unidades-curriculares/consultar_uc',
      dataSrc: 'data',
    },
    columns: [
      { data: 'codigo' },
      { data: 'nombre' },
      { data: 'trayecto' },
      { data: 'fase' },
      { data: 'unidades_credito' },
      {
        data: null,
        orderable: false,
        render: () => '<button class="btn btn-outline-primary btn-sm"><i class="bi bi-pencil"></i></button>',
      },
    ],
    language: {
      url: '/plugins/datatables/es-ES.json',
    },
    responsive: true,
    autoWidth: false,
    order: [[2, 'asc'], [3, 'asc'], [0, 'asc']], // Ordena por Trayecto, luego Fase, luego Código
    pageLength: 10,
    dom: '<"d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3"Bf>rt<"d-flex flex-wrap align-items-center justify-content-between gap-2 pt-3"<"d-inline-flex"i><"d-inline-flex"p>>',
    buttons: [
      {
        extend: 'excelHtml5',
        text: '<i class="bi bi-file-earmark-excel me-1"></i>Excel',
        className: 'btn btn-success',
        titleAttr: 'Exportar a Excel',
        exportOptions: {
          columns: [0, 1, 2, 3, 4] // Exporta todo menos las Acciones
        }
      },
      {
        extend: 'pdfHtml5',
        text: '<i class="bi bi-file-earmark-pdf me-1"></i>PDF',
        className: 'btn btn-danger',
        titleAttr: 'Exportar a PDF',
        exportOptions: {
          columns: [0, 1, 2, 3, 4] // Exporta todo menos las Acciones
        }
      }
    ],
    initComplete: function () {
      initEditarUC(dataTable); // ← ¡Esto mantiene vivo tu botón de editar!
    }
  });
};

export const initConsultarUC = () => {
  initAdminShell();
  inicializarDataTable();
};

const initEditarUC = (dataTable) => {
  const modal = document.getElementById('modalEditarUC');
  if (!modal) return;

  const form = document.getElementById('formEditarUC');
  const idInput = document.getElementById('editar_id');
  const idTrayecto = document.getElementById('editar_id_trayecto');
  const idFase = document.getElementById('editar_id_fase');
  const codigoInput = document.getElementById('editar_codigo');
  const nombreInput = document.getElementById('editar_nombre');
  const ucInput = document.getElementById('editar_unidades_credito');

  let trayectosCargados = false;
  let fasesCache = {};

  const cargarTrayectosEdit = async () => {
    if (trayectosCargados) return;
    try {
      const response = await apiClient.get('a/unidades-curriculares/obtener_trayectos');
      const trayectos = response.data || [];
      trayectos.forEach(t => {
        const opt = document.createElement('option');
        opt.value = t.id;
        opt.textContent = t.nombre;
        idTrayecto.appendChild(opt);
      });
      trayectosCargados = true;
    } catch (error) {
      console.error('Error al cargar trayectos:', error);
    }
  };

  const cargarFasesEdit = async (trayectoId) => {
    if (fasesCache[trayectoId]) {
      return fasesCache[trayectoId];
    }
    try {
      const response = await apiClient.get(`a/unidades-curriculares/obtener_fases?id_trayecto=${trayectoId}`);
      const fases = response.data || [];
      fasesCache[trayectoId] = fases;
      return fases;
    } catch {
      return [];
    }
  };

  idTrayecto.addEventListener('change', async () => {
    const val = idTrayecto.value;
    idFase.innerHTML = '<option value="">Cargando...</option>';
    if (!val) {
      idFase.innerHTML = '<option value="">Primero selecciona un trayecto</option>';
      return;
    }
    const fases = await cargarFasesEdit(val);
    idFase.innerHTML = '<option value="">Seleccionar fase</option>';
    fases.forEach(f => {
      const opt = document.createElement('option');
      opt.value = f.id;
      opt.textContent = f.nombre;
      idFase.appendChild(opt);
    });
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

  dataTable.on('click', 'tbody .btn-outline-primary', async function () {
    const data = dataTable.row(this.closest('tr')).data();

    idInput.value = data.id;
    codigoInput.value = data.codigo;
    nombreInput.value = data.nombre;
    ucInput.value = data.unidades_credito;

    form.querySelectorAll('.is-invalid, .is-valid').forEach(el => el.classList.remove('is-invalid', 'is-valid'));

    await cargarTrayectosEdit();
    idTrayecto.value = data.id_trayecto;

    const fases = await cargarFasesEdit(data.id_trayecto);
    idFase.innerHTML = '<option value="">Seleccionar fase</option>';
    fases.forEach(f => {
      const opt = document.createElement('option');
      opt.value = f.id;
      opt.textContent = f.nombre;
      if (f.id == data.id_fase) opt.selected = true;
      idFase.appendChild(opt);
    });

    new bootstrap.Modal(modal).show();
  });

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

    const btnSubmit = form.querySelector('[type="submit"]');
    const originalText = btnSubmit.innerHTML;
    btnSubmit.disabled = true;
    btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Actualizando...';

    try {
      const payload = {
        id: parseInt(idInput.value),
        id_fase: parseInt(idFase.value),
        codigo: codigoInput.value.trim(),
        nombre: nombreInput.value.trim(),
        unidades_credito: parseInt(ucInput.value),
      };

      await apiClient.post('a/unidades-curriculares/actualizar', payload);

      CevAlert.success({
        title: 'Actualización Exitosa',
        text: 'La unidad curricular ha sido actualizada correctamente.',
        confirmButtonText: 'Aceptar',
      });

      bootstrap.Modal.getInstance(modal).hide();
      dataTable.ajax.reload(null, false);
    } catch (error) {
      CevAlert.error({
        title: 'Error al actualizar',
        text: error.message || 'Ocurrió un error inesperado.',
        confirmButtonText: 'Aceptar',
      });
    } finally {
      btnSubmit.disabled = false;
      btnSubmit.innerHTML = originalText;
    }
  });
};

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initConsultarUC);
} else {
  initConsultarUC();
}
