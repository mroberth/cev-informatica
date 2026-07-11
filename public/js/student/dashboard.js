import { apiClient } from '/js/api/client.js';
import { obtenerDatosUsuario } from '/js/modules/student/common.js';

function initUserInfo() {
  const datosUsuario = obtenerDatosUsuario();
  const nombreEl = document.getElementById('nombreEstudiante');
  if (nombreEl) {
    nombreEl.textContent = datosUsuario.nombre || datosUsuario.correo || 'Estudiante';
  }
}

function initCalendar() {
  const container = document.getElementById('calendarContainer');
  if (!container || typeof CevCalendar === 'undefined') return;

  new CevCalendar(container, {
    events: [],
    onDayClick: (dateStr, events) => {
      console.log('Día seleccionado:', dateStr, events);
    },
    onEventClick: (event) => {
      console.log('Evento:', event);
    },
  });
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

document.addEventListener('DOMContentLoaded', () => {
  initUserInfo();
  initCalendar();
  cargarMateriasResumen();

  const btnEvento = document.getElementById('btnAgregarEvento');
  if (btnEvento) {
    btnEvento.addEventListener('click', () => {
      alert('Funcionalidad de agregar evento próximamente.');
    });
  }
});
