const NOTIFICACIONES_STORAGE_KEY = 'notif_last_id';

function getLastId() {
  return parseInt(sessionStorage.getItem(NOTIFICACIONES_STORAGE_KEY) || '0', 10);
}

function setLastId(id) {
  sessionStorage.setItem(NOTIFICACIONES_STORAGE_KEY, String(id));
}

function obtenerToken() {
  return localStorage.getItem('token');
}

function renderNotificaciones(lista) {
  const container = document.getElementById('notificacionesLista');
  if (!container) return;

  if (!lista.length) {
    container.innerHTML = '<p class="text-muted text-center small my-3">No hay notificaciones nuevas.</p>';
    return;
  }

  container.innerHTML = lista.map(n => {
    const fecha = new Date(n.creado_en).toLocaleString('es-VE', {
      day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit'
    });
    const leidaClass = n.leido ? '' : 'fw-bold';
    const ruta = obtenerRutaNotificacion(n);
    return `
      <a class="dropdown-item ${leidaClass}" href="${ruta}" data-id="${n.id}" style="white-space:normal; border-bottom:1px solid #eee;">
        <div class="d-flex justify-content-between align-items-start">
          <small class="text-muted">${fecha}</small>
          <button class="btn btn-sm p-0 text-muted marcar-leida-btn" data-id="${n.id}" title="Marcar como leída" style="line-height:1;">
            <i class="bi bi-check2"></i>
          </button>
        </div>
        <strong class="d-block small">${n.titulo}</strong>
        ${n.mensaje ? `<span class="d-block text-muted small">${n.mensaje}</span>` : ''}
      </a>
    `;
  }).join('');
}

function obtenerRutaNotificacion(n) {
  if (n.tipo_referencia === 'evaluacion' || n.tipo_referencia === 'calificacion') {
    const materiaId = n.id_referencia;
    return `/u/materia/${materiaId}`;
  }
  return '#';
}

function actualizarBadge(count) {
  const badge = document.getElementById('notificationBadge');
  if (!badge) return;
  if (count > 0) {
    badge.textContent = count > 99 ? '99+' : count;
    badge.style.display = 'inline';
  } else {
    badge.style.display = 'none';
  }
}

async function cargarNotificaciones() {
  try {
    const token = obtenerToken();
    const res = await fetch('/notifications', {
      headers: token ? { 'Authorization': `Bearer ${token}` } : {},
    });
    if (!res.ok) return;
    const json = await res.json();
    const data = json.data || [];
    actualizarBadge(json.no_leidas || 0);
    renderNotificaciones(data);
    if (data.length > 0) {
      const maxId = Math.max(...data.map(n => n.id));
      setLastId(maxId);
    }
  } catch {
    // ignore
  }
}

function iniciarSSE() {
  const token = obtenerToken();
  if (!token) return;

  const evtSource = new EventSource('/notifications/stream');

  evtSource.addEventListener('notifications', (e) => {
    try {
      const data = JSON.parse(e.data);
      if (data.count > 0) {
        actualizarBadge(data.count);
        cargarNotificaciones();
      }
    } catch {
      // ignore
    }
  });

  evtSource.addEventListener('error', () => {
    evtSource.close();
    setTimeout(iniciarSSE, 5000);
  });
}

async function marcarLeida(id) {
  try {
    const token = obtenerToken();
    await fetch(`/notifications/${id}/read`, {
      method: 'POST',
      headers: token ? { 'Authorization': `Bearer ${token}` } : {},
    });
    cargarNotificaciones();
  } catch {
    // ignore
  }
}

async function marcarTodasLeidas() {
  try {
    const token = obtenerToken();
    await fetch('/notifications/read-all', {
      method: 'POST',
      headers: token ? { 'Authorization': `Bearer ${token}` } : {},
    });
    cargarNotificaciones();
  } catch {
    // ignore
  }
}

function initNotificaciones() {
  const bell = document.getElementById('notificationBell');
  if (!bell) return;

  document.addEventListener('click', (e) => {
    const btn = e.target.closest('.marcar-leida-btn');
    if (btn) {
      e.preventDefault();
      e.stopPropagation();
      marcarLeida(btn.dataset.id);
    }
  });

  document.getElementById('marcarTodasLeidas')?.addEventListener('click', (e) => {
    e.preventDefault();
    e.stopPropagation();
    marcarTodasLeidas();
  });

  bell.addEventListener('click', () => {
    cargarNotificaciones();
  });

  cargarNotificaciones();
  iniciarSSE();
}

document.addEventListener('DOMContentLoaded', initNotificaciones);
