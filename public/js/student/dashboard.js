import { apiClient } from '/js/api/client.js';
import { obtenerDatosUsuario } from '/js/modules/student/common.js';

async function initUserInfo() {
  const datosUsuario = obtenerDatosUsuario();
  const nombreEl = document.getElementById('nombreEstudiante');
  if (nombreEl) {
    const nombreCompleto = (datosUsuario.nombre + ' ' + datosUsuario.apellido).trim();
    nombreEl.textContent = nombreCompleto || datosUsuario.correo || 'Estudiante';
  }

  const trayectoEl = document.getElementById('trayectoEstudiante');
  if (!trayectoEl) return;

  try {
    const res = await apiClient.get('u/dashboard/data');
    const info = res.data || {};
    trayectoEl.textContent = info.trayecto || '—';
  } catch {
    trayectoEl.textContent = '—';
  }
}

async function initCalendar() {
  const container = document.getElementById('calendarContainer');
  if (!container || typeof CevCalendar === 'undefined') return;

  const calendar = new CevCalendar(container, {
    events: [],
    onDayClick: (dateStr, events) => {
      console.log('Día seleccionado:', dateStr, events);
    },
    onEventClick: (event) => {
      const materiaId = event.materia_id || event.extendedProps?.materia_id;
      if (materiaId) {
        window.location.href = `/u/materia/${materiaId}`;
      }
    },
  });

  try {
    const res = await apiClient.get('u/calendar/events');
    const eventos = res.data || [];
    if (eventos.length) {
      calendar.setEvents(eventos);
    }
  } catch {
    // calendar stays empty
  }
}

async function cargarMateriasResumen() {
  const container = document.getElementById('materiasResumen');
  if (!container) return;
  try {
    const res = await apiClient.get('u/mis-cursos/data');
    const materias = res.data || [];
    if (!materias.length) {
      container.innerHTML = '<p class="cev-student-empty">No estás inscrito en ninguna materia.</p>';
      return;
    }
    const colors = ['#0d6efd', '#198754', '#dc3545', '#fd7e14', '#6f42c1', '#0dcaf0', '#e83e8c', '#20c997'];
    container.innerHTML = materias.slice(0, 5).map((m, i) => `
      <a href="/u/materia/${m.id}" class="cev-student-subject-card">
        <div class="cev-student-subject-icon" style="background:${colors[i % colors.length]}">
          <i class="bi bi-book"></i>
        </div>
        <div class="cev-student-subject-info">
          <strong>${m.nombre}</strong>
          <span>${m.trayecto || ''}</span>
        </div>
      </a>
    `).join('');
    if (materias.length > 5) {
      container.innerHTML += `<a href="/u/mis-cursos" class="cev-student-subject-card" style="border-style:dashed;justify-content:center;">
        <span style="color:var(--student-muted)">Ver todas (${materias.length})</span>
      </a>`;
    }
  } catch {
    container.innerHTML = '<p class="cev-student-empty">Error al cargar materias.</p>';
  }
}

async function cargarProximasTareas() {
  const container = document.getElementById('proximasTareas');
  if (!container) return;
  try {
    const res = await apiClient.get('u/dashboard/proximas');
    const { proximas = [], resumen_notas = [] } = res.data || {};

    const sinNota = proximas.filter(t => t.urgencia !== 'completada');
    if (!sinNota.length) {
      container.innerHTML = '<p class="cev-student-empty">No tienes tareas pendientes. ¡Al día!</p>';
      return;
    }

    container.innerHTML = sinNota.slice(0, 5).map(t => {
      let badge, badgeClass;
      if (t.urgencia === 'vencida') {
        badge = 'VENCIDA';
        badgeClass = 'urg-badge-vencida';
      } else if (t.urgencia === 'proxima') {
        badge = `${Math.abs(t.dias_restantes)} día${Math.abs(t.dias_restantes) !== 1 ? 's' : ''}`;
        badgeClass = 'urg-badge-proxima';
      } else {
        badge = t.dias_restantes > 0 ? `${t.dias_restantes} día${t.dias_restantes !== 1 ? 's' : ''}` : 'Hoy';
        badgeClass = 'urg-badge-pendiente';
      }

      return `
        <a href="/u/materia/${t.materia_id}" class="cev-student-task-card" style="border-left-color:${t.color}">
          <div class="cev-student-task-body">
            <strong>${t.titulo}</strong>
            <span class="cev-student-task-materia">${t.materia_nombre}</span>
          </div>
          <span class="urg-badge ${badgeClass}">${badge}</span>
        </a>
      `;
    }).join('');
  } catch {
    container.innerHTML = '<p class="cev-student-empty">Error al cargar.</p>';
  }
}

document.addEventListener('DOMContentLoaded', () => {
  initUserInfo();
  initCalendar();
  cargarMateriasResumen();
  cargarProximasTareas();

  const btnEvento = document.getElementById('btnAgregarEvento');
  if (btnEvento) {
    btnEvento.addEventListener('click', () => {
      alert('Funcionalidad de agregar evento próximamente.');
    });
  }
});
