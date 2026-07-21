import { apiClient } from '/js/api/client.js';

async function cargarMaterias() {
  const container = document.getElementById('profMateriasLista');
  if (!container) return;

  try {
    const res = await apiClient.get('p/materias/data');
    const materias = res.data || [];

    if (!materias.length) {
      container.innerHTML = '<p class="cev-prof-empty">No tienes materias asignadas.</p>';
      return;
    }

    const colores = ['#0d6efd', '#198754', '#dc3545', '#fd7e14', '#6f42c1', '#0dcaf0', '#e83e8c', '#20c997'];

    container.innerHTML = materias.map((m, i) => `
      <a href="/p/materias/${m.asignacion_id}" class="cev-prof-materia-card">
        <div class="cev-prof-materia-badge" style="background:${colores[i % colores.length]}">
          <i class="bi bi-book-fill"></i>
        </div>
        <div class="cev-prof-materia-card-body">
          <strong>${m.nombre}</strong>
          <span class="cev-prof-materia-code">${m.codigo}</span>
          <span class="cev-prof-materia-meta">
            ${m.trayecto} &middot; Secci&oacute;n ${m.codigo_seccion} &middot; ${m.turno}
          </span>
          <span class="cev-prof-materia-periodo">${m.periodo}</span>
        </div>
        <div class="cev-prof-materia-card-footer">
          <span>${m.total_recursos} recursos</span>
        </div>
      </a>
    `).join('');
  } catch {
    container.innerHTML = '<p class="cev-prof-empty">Error al cargar materias.</p>';
  }
}

document.addEventListener('DOMContentLoaded', cargarMaterias);
