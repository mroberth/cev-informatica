import { initAdminShell } from '/js/modules/admin/common.js';
import { apiClient } from '/js/api/client.js';

let charts = {};

const COLORES = {
  primary: '#1a2a4a',
  success: '#198754',
  danger: '#dc3545',
  warning: '#ffc107',
  info: '#0dcaf0',
  gray: '#6c757d',
  palette: ['#1a2a4a', '#2d4a7a', '#4a7ab5', '#7a9fc9', '#a0bcd9',
            '#198754', '#28a745', '#20c997', '#17a2b8', '#0dcaf0',
            '#ffc107', '#fd7e14', '#dc3545', '#e83e8c', '#6f42c1'],
};

let dataCache = null;

const destruirChart = (id) => {
  if (charts[id]) {
    charts[id].destroy();
    delete charts[id];
  }
};

const crearChartBarra = (id, canvasId, labels, data, label, color) => {
  const ctx = document.getElementById(canvasId);
  if (!ctx) return;
  destruirChart(id);
  charts[id] = new Chart(ctx, {
    type: 'bar',
    data: {
      labels,
      datasets: [{
        label,
        data,
        backgroundColor: Array.isArray(color) ? color : COLORES.palette.slice(0, data.length),
        borderRadius: 4,
      }],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
      },
      scales: {
        y: { beginAtZero: true },
      },
    },
  });
};

const crearChartDona = (id, canvasId, labels, data) => {
  const ctx = document.getElementById(canvasId);
  if (!ctx) return;
  destruirChart(id);
  charts[id] = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels,
      datasets: [{
        data,
        backgroundColor: COLORES.palette.slice(0, data.length),
        borderWidth: 0,
      }],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom',
          labels: { boxWidth: 12, padding: 12 },
        },
      },
    },
  });
};

const crearChartLinea = (id, canvasId, labels, data, label) => {
  const ctx = document.getElementById(canvasId);
  if (!ctx) return;
  destruirChart(id);
  charts[id] = new Chart(ctx, {
    type: 'line',
    data: {
      labels,
      datasets: [{
        label,
        data,
        borderColor: COLORES.primary,
        backgroundColor: 'rgba(26,42,74,0.08)',
        fill: true,
        tension: 0.3,
        pointBackgroundColor: COLORES.primary,
        pointRadius: 4,
      }],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
      },
      scales: {
        y: { beginAtZero: true },
      },
    },
  });
};

const renderizarReportes = (data) => {
  dataCache = data;

  // KPI cards
  const est = data.estudiantes;
  const rend = data.rendimiento;
  const doc = data.docentes;

  document.getElementById('kpiEstudiantes').textContent = est.total;
  document.getElementById('kpiPromedio').textContent = rend.promedio_general || '—';
  const totalCalif = rend.total_calificadas || 1;
  document.getElementById('kpiAprobacion').textContent = totalCalif > 0
    ? Math.round(rend.aprobados / totalCalif * 100) + '%'
    : '—';
  document.getElementById('kpiDocentes').textContent = doc.total;

  // Students by trayecto (bar)
  const trayectoLabels = est.por_trayecto.map(t => t.trayecto);
  const trayectoData = est.por_trayecto.map(t => parseInt(t.cantidad));
  crearChartBarra('estTrayecto', 'chartEstudiantesTrayecto', trayectoLabels, trayectoData, 'Estudiantes');

  // Grade distribution (bar)
  const dist = data.distribucion_notas;
  crearChartBarra('distNotas', 'chartDistribucionNotas', dist.labels, dist.data, 'Calificaciones',
    ['#dc3545', '#ffc107', '#17a2b8', '#198754']);

  // Rendimiento por materia (bar - top 10)
  const materias = rend.por_materia.slice(0, 10);
  const matLabels = materias.map(m => m.materia.length > 25 ? m.materia.substring(0, 22) + '...' : m.materia);
  const matPromedio = materias.map(m => parseFloat(m.promedio));
  crearChartBarra('rendMateria', 'chartRendimientoMateria', matLabels, matPromedio, 'Promedio', COLORES.primary);

  // Inscripciones trend (line)
  const insc = data.inscripciones;
  const inscLabels = insc.map(i => i.periodo);
  const inscData = insc.map(i => parseInt(i.total));
  crearChartLinea('tendencia', 'chartTendencia', inscLabels, inscData, 'Inscripciones');

  // Top docentes (bar horizontal - use bar with indexAxis)
  const topDoc = doc.top_docentes;
  if (topDoc.length > 0) {
    const docLabels = topDoc.map(d => d.docente.length > 20 ? d.docente.substring(0, 18) + '...' : d.docente);
    const docData = topDoc.map(d => parseInt(d.materias));
    const ctx = document.getElementById('chartTopDocentes');
    if (ctx) {
      destruirChart('topDocentes');
      charts.topDocentes = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: docLabels,
          datasets: [{
            label: 'Materias',
            data: docData,
            backgroundColor: COLORES.palette.slice(0, docData.length),
            borderRadius: 4,
          }],
        },
        options: {
          indexAxis: 'y',
          responsive: true,
          maintainAspectRatio: false,
          plugins: { legend: { display: false } },
          scales: {
            x: { beginAtZero: true, ticks: { stepSize: 1 } },
          },
        },
      });
    }
  }

  // Estado estudiantes (doughnut)
  const estadoLabels = est.por_estado.map(e => e.estado_academico);
  const estadoData = est.por_estado.map(e => parseInt(e.cantidad));
  crearChartDona('estadoEst', 'chartEstadoEstudiantes', estadoLabels, estadoData);

  document.getElementById('ultimaActualizacion').textContent = 'Última actualización: ' + new Date().toLocaleString('es-VE');
};

const cargarDatos = async () => {
  const btn = document.getElementById('btnActualizar');
  const originalHtml = btn.innerHTML;
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Cargando...';

  try {
    const params = new URLSearchParams();
    const periodo = document.getElementById('filtroPeriodo').value;
    if (periodo) params.set('periodo', periodo);
    const qs = params.toString();

    const response = await apiClient.get('a/reportes/data' + (qs ? '?' + qs : ''));
    renderizarReportes(response.data);
  } catch (error) {
    console.error('Error al cargar reportes:', error);
  } finally {
    btn.disabled = false;
    btn.innerHTML = originalHtml;
  }
};

const llenarFiltros = (filtros) => {
  const select = document.getElementById('filtroPeriodo');
  select.innerHTML = '<option value="">Todos los períodos</option>' +
    filtros.periodos.map(p => `<option value="${p.id}">${p.nombre}</option>`).join('');
};

const initReportes = () => {
  initAdminShell();
  cargarDatos();

  document.getElementById('btnActualizar').addEventListener('click', cargarDatos);
};

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initReportes);
} else {
  initReportes();
}
