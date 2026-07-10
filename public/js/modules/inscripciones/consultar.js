import { initAdminShell } from '/js/modules/admin/common.js';
import { apiClient } from '/js/api/client.js';
import { CevAlert } from '/js/utils/CevAlert.js';

let dataTable = null;

const inicializarDataTable = () => {
  dataTable = new DataTable('#tablaInscripciones', {
    ajax: {
      url: '/a/inscripciones/consultar_data',
      dataSrc: 'data',
    },
    columns: [
      { data: 'periodo' },
      { data: 'codigo_seccion' },
      { data: 'trayecto' },
      {
        data: null,
        render: (row) => `${row.est_tipo_cedula}${row.est_cedula}`,
      },
      {
        data: null,
        render: (row) => `${row.est_nombre} ${row.est_apellido}`,
      },
      { data: 'fecha_inscripcion' },
      {
        data: 'estado',
        render: (data) => {
          const badge = data === 'Cursando'
            ? 'bg-success text-white'
            : 'bg-secondary text-white';
          return `<span class="badge ${badge}">${data}</span>`;
        },
      },
      {
        data: null,
        orderable: false,
        render: () => '<button class="btn btn-outline-danger btn-sm btn-eliminar"><i class="bi bi-trash"></i></button>',
      },
    ],
    language: {
      url: '/plugins/datatables/es-ES.json',
    },
    responsive: true,
    autoWidth: false,
    pageLength: 10,
    order: [[0, 'desc'], [1, 'asc']],
    dom: '<"d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3"Bf>rt<"d-flex flex-wrap align-items-center justify-content-between gap-2 pt-3"<"d-inline-flex"i><"d-inline-flex"p>>',
    buttons: [
      {
        extend: 'excelHtml5',
        text: '<i class="bi bi-file-earmark-excel me-1"></i>Excel',
        className: 'btn btn-success',
        titleAttr: 'Exportar a Excel',
        exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] }
      },
      {
        extend: 'pdfHtml5',
        text: '<i class="bi bi-file-earmark-pdf me-1"></i>PDF',
        className: 'btn btn-danger',
        titleAttr: 'Exportar a PDF',
        exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] }
      },
    ],
    initComplete: function () {
      initEliminarInscripcion(dataTable);
    },
  });
};

const initEliminarInscripcion = (dataTable) => {
  dataTable.on('click', '.btn-eliminar', async function () {
    const data = dataTable.row(this.closest('tr')).data();

    const resultado = await CevAlert.question({
      title: '¿Eliminar inscripción?',
      text: `Se eliminará la inscripción de ${data.est_nombre} ${data.est_apellido} en ${data.codigo_seccion}.`,
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar',
    });

    if (!resultado.isConfirmed) return;

    try {
      await apiClient.post('a/inscripciones/eliminar', { id: data.id });

      CevAlert.success({
        title: 'Eliminado',
        text: 'La inscripción ha sido eliminada correctamente.',
        confirmButtonText: 'Aceptar',
      });

      dataTable.ajax.reload(null, false);
    } catch (error) {
      CevAlert.error({
        title: 'Error al eliminar',
        text: error.message || 'Ocurrió un error inesperado.',
        confirmButtonText: 'Aceptar',
      });
    }
  });
};

const initConsultarInscripciones = () => {
  initAdminShell();
  inicializarDataTable();
};

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initConsultarInscripciones);
} else {
  initConsultarInscripciones();
}
