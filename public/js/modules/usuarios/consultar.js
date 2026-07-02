import { initAdminShell } from '/js/modules/admin/common.js';
import { initEditarUsuarios } from '/js/modules/usuarios/editar.js';
import { initEliminarUsuarios } from '/js/modules/usuarios/eliminar.js';

let dataTable = null;

const inicializarDataTable = () => {
  dataTable = new DataTable('#tablaUsuarios', {
    ajax: {
      url: '/a/usuarios/consultar_usuarios',
      dataSrc: 'data',
    },
    columns: [
      { data: 'nombre' },
      { data: 'apellido' },
      { data: 'correo' },
      { data: 'rol' },
      {
        data: 'estado',
        render: (data) => {
          const badge = data === 'activo'
            ? 'bg-success'
            : 'bg-secondary';
          return `<span class="badge ${badge}">${data}</span>`;
        }
      },
      {
        data: null,
        render: () => `
          <div class="d-flex gap-1">
            <button class="btn btn-sm btn-outline-primary" title="Editar">
              <i class="bi bi-pencil"></i>
            </button>
            <button class="btn btn-sm btn-outline-danger" title="Eliminar">
              <i class="bi bi-trash"></i>
            </button>
          </div>
        `
      }
    ],
    language: {
      url: '/plugins/datatables/es-ES.json'
    },
    responsive: true,
    autoWidth: false,
    order: [[0, 'asc']],
    pageLength: 10,
    dom: '<"d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3"Bf>rt<"d-flex flex-wrap align-items-center justify-content-between gap-2 pt-3"<"d-inline-flex"i><"d-inline-flex"p>>',
    buttons: [
      {
        extend: 'excelHtml5',
        text: '<i class="bi bi-file-earmark-excel me-1"></i>Excel',
        className: 'btn btn-success',
        titleAttr: 'Exportar a Excel',
        exportOptions: {
          columns: ':visible:not(:last-child)'
        }
      },
      {
        extend: 'pdfHtml5',
        text: '<i class="bi bi-file-earmark-pdf me-1"></i>PDF',
        className: 'btn btn-danger',
        titleAttr: 'Exportar a PDF',
        exportOptions: {
          columns: ':visible:not(:last-child)'
        }
      }
    ],
    initComplete: function () {
      initEditarUsuarios(dataTable);
      initEliminarUsuarios(dataTable);
    }
  });
};

const initConsultarUsuarios = () => {
  initAdminShell();
  inicializarDataTable();
};

document.addEventListener('DOMContentLoaded', () => {
  initConsultarUsuarios();
  document.addEventListener('cev:usuario-actualizado', () => {
    if (dataTable) {
      dataTable.ajax.reload(null, false);
    }
  });
});
