import { initAdminShell } from '/js/modules/admin/common.js';
import { apiClient } from '/js/api/client.js';
import { CevAlert } from '/js/utils/CevAlert.js';

let dataTable = null;

const inicializarDataTable = (usuarios) => {
  dataTable = new DataTable('#tablaUsuarios', {
    data: usuarios,
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
    dom: '<"d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3"Bf>rt<"d-flex flex-wrap align-items-center justify-content-between gap-2"<"d-inline-flex"i><"d-inline-flex"p>>',
    buttons: [
      {
        extend: 'excelHtml5',
        text: '<i class="bi bi-file-earmark-excel me-1"></i>Excel',
        className: 'btn btn-success btn-sm',
        titleAttr: 'Exportar a Excel',
        exportOptions: {
          columns: ':visible:not(:last-child)'
        }
      },
      {
        extend: 'pdfHtml5',
        text: '<i class="bi bi-file-earmark-pdf me-1"></i>PDF',
        className: 'btn btn-danger btn-sm',
        titleAttr: 'Exportar a PDF',
        exportOptions: {
          columns: ':visible:not(:last-child)'
        }
      }
    ]
  });
};

const cargarUsuarios = async () => {
  try {
    const response = await apiClient.get('a/usuarios/consultar_usuarios');
    const usuarios = response.data ?? response;

    if (dataTable) {
      dataTable.destroy();
      dataTable = null;
    }

    inicializarDataTable(usuarios);
  } catch (error) {
    CevAlert.error({
      title: 'Error al cargar usuarios',
      text: error.message || 'Ocurrió un error al obtener el listado de usuarios.',
    });
  }
};

const initConsultarUsuarios = () => {
  initAdminShell();
  cargarUsuarios();
};

document.addEventListener('DOMContentLoaded', initConsultarUsuarios);
