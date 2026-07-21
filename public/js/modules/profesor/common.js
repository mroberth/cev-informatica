import { CevAlert } from '/js/utils/CevAlert.js';

const API_BASE = '/';
let inicializado = false;

function getCookie(name) {
  const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
  return match ? decodeURIComponent(match[2]) : null;
}

function decodificarBase64Url(segmento) {
  const normalizado = segmento.replace(/-/g, '+').replace(/_/g, '/');
  const relleno = normalizado.padEnd(Math.ceil(normalizado.length / 4) * 4, '=');
  return atob(relleno);
}

export function obtenerTokenAcceso() {
  return localStorage.getItem('token') || getCookie('access_token') || '';
}

export function obtenerDatosToken() {
  const token = obtenerTokenAcceso();
  if (!token) return null;
  try {
    const partes = token.split('.');
    if (partes.length !== 3) return null;
    return JSON.parse(decodificarBase64Url(partes[1]));
  } catch { return null; }
}

export function obtenerDatosUsuario() {
  const datosToken = obtenerDatosToken();
  const usuario = datosToken?.user || {};
  return {
    token: obtenerTokenAcceso(),
    payload: datosToken,
    nombre: localStorage.getItem('user_nombre') || usuario.nombre || '',
    apellido: localStorage.getItem('user_apellido') || usuario.apellido || '',
    correo: localStorage.getItem('user_email') || usuario.correo || '',
    rol: localStorage.getItem('user_rol') || usuario.rol || '',
  };
}

function limpiarSesionYRedirigir(reason) {
  localStorage.clear();
  if (reason) {
    sessionStorage.setItem('redirect_reason', reason);
  }
  window.location.href = '/login';
}

export function initProfesorShell() {
  if (inicializado) return;
  inicializado = true;

  const token = obtenerTokenAcceso();
  if (!token) {
    limpiarSesionYRedirigir('no_auth');
    return;
  }

  const datos = obtenerDatosUsuario();
  const inicial = (datos.nombre || datos.correo || 'P')[0].toUpperCase();
  const avatar = document.getElementById('avatarProfesor');
  if (avatar) avatar.textContent = inicial;

  const info = document.getElementById('infoProfesor');
  if (info) {
    const nombreCompleto = (datos.nombre + ' ' + datos.apellido).trim() || datos.correo;
    info.innerHTML = nombreCompleto + '<br><small class="text-muted">' + (datos.rol || 'Profesor') + '</small>';
  }

  const btnCerrar = document.getElementById('btnCerrarSesionProf');
  if (btnCerrar) {
    btnCerrar.addEventListener('click', async (e) => {
      e.preventDefault();
      const confirmacion = await CevAlert.question({
        title: 'Cerrar sesión',
        text: '¿Deseas cerrar sesión ahora?',
        confirmButtonText: 'Sí, cerrar sesión',
        cancelButtonText: 'Cancelar',
      });
      if (!confirmacion.isConfirmed) return;
      try {
        await fetch(API_BASE + 'logout', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + obtenerTokenAcceso() },
          credentials: 'include',
        });
      } catch { /* ignore */ }
      limpiarSesionYRedirigir('logout_success');
    });
  }
}
