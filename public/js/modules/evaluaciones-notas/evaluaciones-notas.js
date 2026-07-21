import { initAdminShell } from '/js/modules/admin/common.js';
import { apiClient } from '/js/api/client.js';

let dataTable = null;
let filtrosCache = null;

const TIPO_BADGE = {
  'Examen': 'bg-primary',
  'Taller': 'bg-warning text-dark',
  'Proyecto': 'bg-success',
  'Laboratorio': 'bg-info text-dark',
  'Actividad': 'bg-secondary',
};

const llenarFiltros = (filtros) => {
  filtrosCache = filtros;

  const trayectoSelect = document.getElementById('filtroTrayecto');
  trayectoSelect.innerHTML = '<option value="">Todos los trayectos</option>' +
    filtros.trayectos.map(t => `<option value="${t.id}">${t.nombre}</option>`).join('');

  const seccionSelect = document.getElementById('filtroSeccion');
  seccionSelect.innerHTML = '<option value="">Todas las secciones</option>' +
    filtros.secciones.map(s => `<option value="${s.id}">${s.codigo_seccion}</option>`).join('');

  const materiaSelect = document.getElementById('filtroMateria');
  materiaSelect.innerHTML = '<option value="">Todas las materias</option>' +
    filtros.materias.map(m => `<option value="${m.id}">${m.nombre} (${m.codigo})</option>`).join('');
};

const obtenerParamsFiltro = () => {
  const params = {};
  const t = document.getElementById('filtroTrayecto').value;
  const s = document.getElementById('filtroSeccion').value;
  const m = document.getElementById('filtroMateria').value;
  if (t) params.trayecto = t;
  if (s) params.seccion = s;
  if (m) params.materia = m;
  return params;
};

const inicializarDataTable = () => {
  dataTable = new DataTable('#tablaEvaluaciones', {
    ajax: {
      url: '/a/evaluaciones-notas/data',
      data: (d) => {
        const filtros = obtenerParamsFiltro();
        Object.assign(d, filtros);
      },
      dataSrc: (json) => {
        if (json.filtros) llenarFiltros(json.filtros);
        return json.data || [];
      },
    },
    columns: [
      {
        data: null,
        render: (row) => `${row.materia_nombre} <small class="text-muted">(${row.materia_codigo})</small>`,
      },
      { data: 'trayecto' },
      { data: 'codigo_seccion' },
      { data: 'docente_nombre' },
      { data: 'titulo' },
      {
        data: 'tipo',
        render: (data) => {
          const cls = TIPO_BADGE[data] || 'bg-secondary';
          return `<span class="badge ${cls}">${data}</span>`;
        },
      },
      {
        data: 'porcentaje',
        render: (data) => `${parseFloat(data).toFixed(0)}%`,
      },
      {
        data: 'fecha_estimada',
        render: (data) => data ? new Date(data).toLocaleDateString('es-VE') : '-',
      },
      {
        data: null,
        render: (row) => {
          const total = parseInt(row.total_estudiantes) || 0;
          const calif = parseInt(row.calificadas) || 0;
          const pct = total > 0 ? Math.round(calif / total * 100) : 0;
          return `<span>${calif}/${total}</span>
            <div class="progress" style="height:4px;width:60px">
              <div class="progress-bar" style="width:${pct}%;background:#1a2a4a"></div>
            </div>`;
        },
      },
      {
        data: 'promedio_nota',
        render: (data) => data !== null ? data : '—',
      },
      {
        data: null,
        orderable: false,
        render: (row) => `<button class="btn btn-outline-primary btn-sm btn-ver-notas" data-id="${row.id}" data-titulo="${row.titulo}" data-materia="${row.materia_nombre}" data-seccion="${row.codigo_seccion}"><i class="bi bi-eye me-1"></i>Ver notas</button>`,
      },
    ],
    language: {
      url: '/plugins/datatables/es-ES.json',
    },
    responsive: true,
    autoWidth: false,
    pageLength: 25,
    order: [[0, 'asc']],
    dom: '<"d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3"Bf>rt<"d-flex flex-wrap align-items-center justify-content-between gap-2 pt-3"<"d-inline-flex"i><"d-inline-flex"p>>',
    buttons: [
      {
        extend: 'excelHtml5',
        text: '<i class="bi bi-file-earmark-excel me-1"></i>Excel',
        className: 'btn btn-success',
        titleAttr: 'Exportar a Excel',
        exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 9] },
      },
      {
        extend: 'pdfHtml5',
        text: '<i class="bi bi-file-earmark-pdf me-1"></i>PDF',
        className: 'btn btn-danger',
        titleAttr: 'Exportar a PDF',
        exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 9] },
      },
    ],
    initComplete: function () {
      initVerNotas();
    },
  });
};

const initVerNotas = () => {
  const modal = document.getElementById('modalVerNotas');
  if (!modal) return;
  const modalEvalTitulo = document.getElementById('modalEvalTitulo');
  const modalEvalMeta = document.getElementById('modalEvalMeta');
  const tbody = document.querySelector('#tablaEstudiantes tbody');

  dataTable.on('click', '.btn-ver-notas', async function () {
    const btn = this;
    const id = btn.dataset.id;
    const titulo = btn.dataset.titulo;
    const materia = btn.dataset.materia;
    const seccion = btn.dataset.seccion;

    modalEvalTitulo.textContent = titulo;
    modalEvalMeta.textContent = `${materia} — Sección ${seccion}`;
    tbody.innerHTML = '<tr><td colspan="4" class="text-center py-4"><div class="spinner-border spinner-border-sm me-2"></div>Cargando...</td></tr>';

    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();

    try {
      const response = await apiClient.get(`a/evaluaciones-notas/${id}/estudiantes`);
      const estudiantes = response.data || [];

      if (estudiantes.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-4">No hay estudiantes inscritos en esta sección.</td></tr>';
        return;
      }

      tbody.innerHTML = estudiantes.map((est, i) => {
        const nota = est.nota !== null ? parseFloat(est.nota) : null;
        let notaHtml;
        if (nota === null) {
          notaHtml = '<span class="badge bg-secondary">Sin nota</span>';
        } else if (nota >= 10) {
          notaHtml = `<span class="badge bg-success fs-6">${nota.toFixed(1)}</span>`;
        } else {
          notaHtml = `<span class="badge bg-danger fs-6">${nota.toFixed(1)}</span>`;
        }
        return `<tr>
          <td>${i + 1}</td>
          <td><strong>${est.nombre} ${est.apellido}</strong></td>
          <td>${notaHtml}</td>
          <td class="text-muted small">${est.observaciones || '—'}</td>
        </tr>`;
      }).join('');
    } catch (error) {
      tbody.innerHTML = `<tr><td colspan="4" class="text-center text-danger py-4">Error: ${error.message || 'No se pudieron cargar las notas.'}</td></tr>`;
    }
  });

  modal.addEventListener('hidden.bs.modal', () => {
    tbody.innerHTML = '';
    modalEvalTitulo.textContent = '';
    modalEvalMeta.textContent = '';
  });
};

const initEvaluacionesNotas = () => {
  initAdminShell();
  inicializarDataTable();

  document.getElementById('btnFiltrar').addEventListener('click', () => {
    dataTable.ajax.reload();
  });

  document.getElementById('btnLimpiar').addEventListener('click', () => {
    document.getElementById('filtroTrayecto').value = '';
    document.getElementById('filtroSeccion').value = '';
    document.getElementById('filtroMateria').value = '';
    dataTable.ajax.reload();
  });
};

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initEvaluacionesNotas);
} else {
  initEvaluacionesNotas();
}
