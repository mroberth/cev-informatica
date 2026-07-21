import { apiClient } from '/js/api/client.js';
import { obtenerDatosUsuario } from '/js/modules/profesor/common.js';

async function cargarDashboard() {
  const datos = obtenerDatosUsuario();
  const nombreEl = document.getElementById('profNombre');
  if (nombreEl) {
    nombreEl.textContent = (datos.nombre + ' ' + datos.apellido).trim() || datos.correo || 'Profesor';
  }

  try {
    const res = await apiClient.get('p/dashboard/data');
    const info = res.data || {};

    const espEl = document.getElementById('profEspecialidad');
    if (espEl && info.docente?.especialidad) {
      espEl.textContent = info.docente.especialidad;
    }

    const statMat = document.getElementById('statMaterias');
    if (statMat) statMat.textContent = info.total_materias || 0;

    const statRec = document.getElementById('statRecursos');
    if (statRec) statRec.textContent = info.total_recursos || 0;

    const materias = info.materias || [];
    const container = document.getElementById('profMateriasResumen');
    if (container) {
      if (!materias.length) {
        container.innerHTML = '<p class="cev-prof-empty">No tienes materias asignadas.</p>';
      } else {
        container.innerHTML = materias.slice(0, 6).map(m => `
          <a href="/p/materias/${m.asignacion_id}" class="cev-prof-materia-card">
            <div class="cev-prof-materia-card-body">
              <strong>${m.nombre}</strong>
              <span class="cev-prof-materia-code">${m.codigo}</span>
              <span class="cev-prof-materia-meta">${m.trayecto} &middot; Sec. ${m.codigo_seccion}</span>
            </div>
            <div class="cev-prof-materia-card-footer">
              <span>${m.total_recursos} recursos</span>
            </div>
          </a>
        `).join('');
      }
    }
  } catch {
    const container = document.getElementById('profMateriasResumen');
    if (container) container.innerHTML = '<p class="cev-prof-empty">Error al cargar datos.</p>';
  }
}

document.addEventListener('DOMContentLoaded', cargarDashboard);
