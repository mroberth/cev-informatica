import { apiClient } from '/js/api/client.js';

async function cargarCursos() {
  const grid = document.getElementById('misCursosGrid');
  if (!grid) return;
  try {
    const res = await apiClient.get('u/mis-cursos/data');
    const materias = res.data || [];
    if (!materias.length) {
      grid.innerHTML = '<p class="cev-student-empty">No estás inscrito en ninguna materia.</p>';
      return;
    }
    const colors = ['#0d6efd', '#198754', '#dc3545', '#fd7e14', '#6f42c1', '#0dcaf0', '#e83e8c', '#20c997'];
    grid.innerHTML = materias.map((m, i) => `
      <a href="/u/materia/${m.id}" class="cev-student-course-card">
        <div class="cev-student-course-badge" style="background:${colors[i % colors.length]}">
          <i class="bi bi-book-fill"></i>
        </div>
        <div class="cev-student-course-body">
          <h3>${m.nombre}</h3>
          <p>${m.trayecto || ''} ${m.fase ? '— ' + m.fase : ''}</p>
          <span class="cev-student-course-meta">${m.profesor || 'Sin profesor asignado'}</span>
        </div>
      </a>
    `).join('');
  } catch {
    grid.innerHTML = '<p class="cev-student-empty">Error al cargar materias.</p>';
  }
}

document.addEventListener('DOMContentLoaded', cargarCursos);
