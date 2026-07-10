import { initAdminShell } from '/js/modules/admin/common.js';
import { apiClient } from '/js/api/client.js';
import { CevAlert } from '/js/utils/CevAlert.js';

let dataTable = null;

const inicializarDataTable = () => {
  dataTable = new DataTable('#tablaAsignaciones', {
    ajax: {
      url: '/a/asignacion-docente/consultar_data',
      dataSrc: 'data',
    },
    columns: [
      { data: 'periodo' },
      { data: 'codigo_seccion' },
      { data: 'trayecto' },
      { data: 'uc_codigo' },
      { data: 'uc_nombre' },
      {
        data: null,
        render: (row) => `${row.docente_nombre} ${row.docente_apellido} - ${row.docente_tipo_cedula}${row.docente_cedula}`,
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
    order: [[0, 'desc']],
    dom: '<"d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3"Bf>rt<"d-flex flex-wrap align-items-center justify-content-between gap-2 pt-3"<"d-inline-flex"i><"d-inline-flex"p>>',
    buttons: [
      {
        extend: 'excelHtml5',
        text: '<i class="bi bi-file-earmark-excel me-1"></i>Excel',
        className: 'btn btn-success',
        titleAttr: 'Exportar a Excel',
        exportOptions: { columns: [0, 1, 2, 3, 4, 5] }
      },
      {
        extend: 'pdfHtml5',
        text: '<i class="bi bi-file-earmark-pdf me-1"></i>PDF',
        className: 'btn btn-danger',
        titleAttr: 'Exportar a PDF',
        exportOptions: { columns: [0, 1, 2, 3, 4, 5] }
      },
    ],
    initComplete: function () {
      initEliminarAsignacion(dataTable);
    },
  });
};

const initEliminarAsignacion = (dataTable) => {
  dataTable.on('click', '.btn-eliminar', async function () {
    const data = dataTable.row(this.closest('tr')).data();

    const resultado = await CevAlert.question({
      title: '¿Eliminar asignación?',
      text: `Se eliminará la asignación de ${data.docente_nombre} ${data.docente_apellido} en ${data.uc_nombre} (${data.codigo_seccion}).`,
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar',
    });

    if (!resultado.isConfirmed) return;

    try {
      await apiClient.post('a/asignacion-docente/eliminar', { id: data.id });

      CevAlert.success({
        title: 'Eliminado',
        text: 'La asignación ha sido eliminada correctamente.',
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

const initConsultarAsignacion = () => {
  initAdminShell();
  inicializarDataTable();
};

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initConsultarAsignacion);
} else {
  initConsultarAsignacion();
}
