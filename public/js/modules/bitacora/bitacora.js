import { initAdminShell } from '/js/modules/admin/common.js';

let dataTable = null;

const inicializarDataTable = () => {
  dataTable = new DataTable('#tablaBitacora', {
    ajax: {
      url: '/a/bitacora/consultar_bitacora',
      dataSrc: 'data',
    },
    columns: [
      { data: 'id', visible: false },
      { data: 'nombre_completo' },
      { data: 'accion' },
      { data: 'descripcion' },
      { data: 'direccion_ip' },
      {
        data: 'navegador',
        render: (data) => {
          if (!data) return '';
          const corto = data.length > 50 ? data.substring(0, 50) + '...' : data;
          return `<span title="${data.replace(/"/g, '&quot;')}">${corto}</span>`;
        }
      },
      {
        data: 'fecha',
        render: (data) => {
          if (!data) return '';
          const d = new Date(data);
          return d.toLocaleString('es-VE', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
          });
        }
      }
    ],
    language: {
      url: '/plugins/datatables/es-ES.json'
    },
    responsive: true,
    autoWidth: false,
    order: [[0, 'desc']],
    pageLength: 10,
    dom: '<"d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3"Bf>rt<"d-flex flex-wrap align-items-center justify-content-between gap-2 pt-3"<"d-inline-flex"i><"d-inline-flex"p>>',
    buttons: [
      {
        extend: 'excelHtml5',
        text: '<i class="bi bi-file-earmark-excel me-1"></i>Excel',
        className: 'btn btn-success',
        titleAttr: 'Exportar a Excel',
      },
      {
        extend: 'pdfHtml5',
        text: '<i class="bi bi-file-earmark-pdf me-1"></i>PDF',
        className: 'btn btn-danger',
        titleAttr: 'Exportar a PDF',
        exportOptions: {
          columns: [1, 2, 3, 4, 6]
        }
      }
    ]
  });
};

const initConsultarBitacora = () => {
  initAdminShell();
  inicializarDataTable();
};

document.addEventListener('DOMContentLoaded', () => {
  initConsultarBitacora();
});
