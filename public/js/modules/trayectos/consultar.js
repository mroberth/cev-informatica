import { initAdminShell } from '/js/modules/admin/common.js';
import { apiClient } from '/js/api/client.js';

let dataTable = null;

const inicializarDataTable = () => {
  dataTable = new DataTable('#tablaTrayectos', {
    ajax: {
      url: '/a/trayectos/consultar_trayectos',
      dataSrc: 'data',
    },
    columns: [
      { data: 'trayecto' },
      { data: 'descripcion_trayecto' },
      {
        data: 'fases',
        render: (data) => {
          if (!data || data.length === 0) return '<span class="text-muted">Sin fases</span>';
          const badges = data.map(f =>
            `<span class="badge bg-info text-white me-1 mb-1">${f.fase}</span>`
          );
          return badges.join(' ');
        },
      },
    ],
    language: {
      url: '/plugins/datatables/es-ES.json',
    },
    responsive: true,
    autoWidth: false,
    pageLength: 10,
    order: [[0, 'asc']],
    searching: false,
    paging: false,
    info: false,
    dom: 'rt',
  });
};

const initConsultarTrayectos = () => {
  initAdminShell();
  inicializarDataTable();
};

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initConsultarTrayectos);
} else {
  initConsultarTrayectos();
}
