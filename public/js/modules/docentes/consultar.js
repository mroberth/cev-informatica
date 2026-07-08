import { initAdminShell } from '/js/modules/admin/common.js';
import { apiClient } from '/js/api/client.js';
import { CevAlert } from '/js/utils/CevAlert.js';

let dataTable = null;

const inicializarDataTable = () => {
  dataTable = new DataTable('#tablaDocentes', {
    ajax: {
      url: '/a/docentes/obtener_registrados',
      dataSrc: 'data',
    },
    columns: [
      {
        data: null,
        render: (row) => `${row.tipo_cedula}${row.cedula}`,
      },
      {
        data: null,
        render: (row) => `${row.nombre} ${row.apellido}`,
      },
      { data: 'especialidad' },
      {
        data: 'estado',
        render: (data) => {
          const badge = data === 'Activo'
            ? 'bg-success text-white'
            : 'bg-secondary text-white';
          return `<span class="badge ${badge}">${data}</span>`;
        },
      },
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
    pageLength: 10,
    order: [[1, 'asc']],
    dom: '<"d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3"Bf>rt<"d-flex flex-wrap align-items-center justify-content-between gap-2 pt-3"<"d-inline-flex"i><"d-inline-flex"p>>',
    buttons: [
      {
        extend: 'excelHtml5',
        text: '<i class="bi bi-file-earmark-excel me-1"></i>Excel',
        className: 'btn btn-success',
        titleAttr: 'Exportar a Excel',
        exportOptions: {
          columns: [0, 1, 2, 3]
        }
      },
      {
        extend: 'pdfHtml5',
        text: '<i class="bi bi-file-earmark-pdf me-1"></i>PDF',
        className: 'btn btn-danger',
        titleAttr: 'Exportar a PDF',
        exportOptions: {
          columns: [0, 1, 2, 3]
        }
      }
    ],
    initComplete: function () {
      initEditarDocente(dataTable);
    },
  });
};

const initEditarDocente = (dataTable) => {
  const modal = document.getElementById('modalEditarDocente');
  if (!modal) return;

  const form = document.getElementById('formEditarDocente');
  const idInput = document.getElementById('editar_id');
  const nombreInput = document.getElementById('editar_nombre_completo');
  const especialidadInput = document.getElementById('editar_especialidad');
  const estadoInput = document.getElementById('editar_estado');

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

  function validarEspecialidad() {
    const val = especialidadInput.value.trim();
    if (val.length > 100) {
      showError(especialidadInput, 'La especialidad no debe exceder 100 caracteres.');
      return false;
    }
    clearError(especialidadInput);
    return true;
  }

  function validarEstado() {
    if (!estadoInput.value) {
      showError(estadoInput, 'El estado es obligatorio.');
      return false;
    }
    clearError(estadoInput);
    return true;
  }

  especialidadInput.addEventListener('input', validarEspecialidad);
  estadoInput.addEventListener('change', validarEstado);

  dataTable.on('click', 'tbody .btn-outline-primary', async function () {
    const data = dataTable.row(this.closest('tr')).data();

    idInput.value = data.id;
    nombreInput.value = `${data.nombre} ${data.apellido} - ${data.tipo_cedula}${data.cedula}`;
    especialidadInput.value = data.especialidad;
    estadoInput.value = data.estado;

    form.querySelectorAll('.is-invalid, .is-valid').forEach(el => el.classList.remove('is-invalid', 'is-valid'));

    new bootstrap.Modal(modal).show();
  });

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const validaciones = [
      validarEspecialidad(),
      validarEstado(),
    ];

    if (!validaciones.every(v => v === true)) return;

    const btnSubmit = document.querySelector('button[form="formEditarDocente"]');
    const originalText = btnSubmit.innerHTML;
    btnSubmit.disabled = true;
    btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Actualizando...';

    try {
      const payload = {
        id: parseInt(idInput.value),
        especialidad: especialidadInput.value.trim(),
        estado: estadoInput.value,
      };

      await apiClient.post('a/docentes/actualizar', payload);

      CevAlert.success({
        title: 'Actualización Exitosa',
        text: 'El docente ha sido actualizado correctamente.',
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

const initConsultarDocentes = () => {
  initAdminShell();
  inicializarDataTable();
};

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initConsultarDocentes);
} else {
  initConsultarDocentes();
}
