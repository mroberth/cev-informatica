import { CevAlert } from '/js/utils/CevAlert.js';
import { initAdminShell } from '/js/modules/admin/common.js';

let calendarioDashboard = null;
let modalEventoCalendario = null;

function inicializarDashboard() {
  initAdminShell();
  inicializarCalendarioPrincipal();
}

function inicializarCalendarioPrincipal() {
  const contenedorCalendario = document.getElementById('dashboardCalendar');
  if (!contenedorCalendario || typeof window.CevCalendar !== 'function') {
    return;
  }

  calendarioDashboard = new window.CevCalendar(contenedorCalendario, {
    locale: 'es-VE',
    firstDayOfWeek: 1,
    views: ['dayGridMonth', 'listMonth'],
    initialView: 'dayGridMonth',
    events: construirEventosSemilla(),
    onEventClick: manejarAperturaEvento,
  });

  window.DashboardCalendar = {
    instance: calendarioDashboard,
    setEvents: (eventos) => calendarioDashboard.setEvents(normalizarEventos(eventos)),
    addEvent: (evento) => calendarioDashboard.addEvent(normalizarEvento(evento)),
    removeEvent: (idEvento) => calendarioDashboard.removeEvent(idEvento),
    updateEvent: (idEvento, cambios) => calendarioDashboard.updateEvent(idEvento, cambios),
    getEvents: () => calendarioDashboard.getEvents(),
    getEventsByDate: (fecha) => calendarioDashboard.getEventsForDate(fecha),
    clear: () => calendarioDashboard.setEvents([]),
    loadFromRows: (filasBD) => {
      const eventos = Array.isArray(filasBD)
        ? filasBD.map((fila) => mapearFilaEvento(fila))
        : [];
      calendarioDashboard.setEvents(eventos);
    },
  };
}

function construirEventosSemilla() {
  const hoy = new Date();
  const f1 = formatearFechaISO(hoy);

  const manana = new Date(hoy);
  manana.setDate(manana.getDate() + 1);
  const f2 = formatearFechaISO(manana);

  return [
    {
      id: 1,
      date: f1,
      title: 'Bienvenida al dashboard',
      description: 'Evento de ejemplo para validar la integracion del calendario.',
      time: '08:30',
      type: 'informativo',
      openMode: 'alert',
      color: '#0d6efd',
    },
    {
      id: 2,
      date: f2,
      title: 'Revision academica',
      description: 'Reunion de control con coordinacion academica.',
      time: '10:00',
      type: 'reunion',
      openMode: 'modal',
      color: '#198754',
    },
  ];
}

function manejarAperturaEvento(evento) {
  const eventoNormalizado = normalizarEvento(evento);
  const modo = eventoNormalizado.openMode || resolverModoApertura(eventoNormalizado.type);

  if (modo === 'modal') {
    abrirEventoEnModal(eventoNormalizado);
    return;
  }

  abrirEventoEnAlerta(eventoNormalizado);
}

function resolverModoApertura(tipoEvento) {
  const tiposModal = new Set(['reunion', 'evaluacion', 'acto', 'academico']);
  return tiposModal.has(String(tipoEvento || '').toLowerCase()) ? 'modal' : 'alert';
}

function abrirEventoEnModal(evento) {
  const cuerpoModal = document.getElementById('modalEventoCalendarioContenido');
  const tituloModal = document.getElementById('modalEventoCalendarioTitulo');
  const elementoModal = document.getElementById('modalEventoCalendario');
  if (!cuerpoModal || !tituloModal || !elementoModal || typeof bootstrap === 'undefined') {
    abrirEventoEnAlerta(evento);
    return;
  }

  tituloModal.textContent = evento.title || 'Detalle del evento';
  cuerpoModal.innerHTML = `
    <dl class="calendar-event-meta mb-0">
      <dt>Fecha</dt><dd>${escapeHtml(evento.date || 'No definida')}</dd>
      <dt>Hora</dt><dd>${escapeHtml(evento.time || 'Sin hora')}</dd>
      <dt>Tipo</dt><dd>${escapeHtml(evento.type || 'General')}</dd>
      <dt>Descripcion</dt><dd>${escapeHtml(evento.description || 'Sin descripcion')}</dd>
    </dl>
  `;

  if (!modalEventoCalendario) {
    modalEventoCalendario = new bootstrap.Modal(elementoModal);
  }

  modalEventoCalendario.show();
}

function abrirEventoEnAlerta(evento) {
  CevAlert.info({
    title: evento.title || 'Evento',
    html: `
      <div class="text-start">
        <p class="mb-1"><strong>Fecha:</strong> ${escapeHtml(evento.date || 'No definida')}</p>
        <p class="mb-1"><strong>Hora:</strong> ${escapeHtml(evento.time || 'Sin hora')}</p>
        <p class="mb-1"><strong>Tipo:</strong> ${escapeHtml(evento.type || 'General')}</p>
        <p class="mb-0">${escapeHtml(evento.description || 'Sin descripcion')}</p>
      </div>
    `,
    confirmButtonText: 'Cerrar',
  });
}

function mapearFilaEvento(fila) {
  return normalizarEvento({
    id: fila.id,
    date: fila.fecha,
    title: fila.titulo,
    description: fila.descripcion,
    time: fila.hora,
    type: fila.tipo,
    openMode: fila.modo_apertura,
    color: fila.color,
  });
}

function normalizarEventos(eventos) {
  return Array.isArray(eventos) ? eventos.map((evento) => normalizarEvento(evento)) : [];
}

function normalizarEvento(evento = {}) {
  return {
    id: evento.id ?? null,
    date: evento.date || '',
    title: evento.title || 'Evento',
    description: evento.description || '',
    time: evento.time || '',
    type: evento.type || 'general',
    openMode: evento.openMode || null,
    color: evento.color || '#0d6efd',
  };
}

function formatearFechaISO(fecha) {
  const anio = fecha.getFullYear();
  const mes = String(fecha.getMonth() + 1).padStart(2, '0');
  const dia = String(fecha.getDate()).padStart(2, '0');
  return `${anio}-${mes}-${dia}`;
}

function escapeHtml(texto) {
  const div = document.createElement('div');
  div.textContent = String(texto || '');
  return div.innerHTML;
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', inicializarDashboard);
} else {
  inicializarDashboard();
}
