import { CevAlert } from '/js/utils/CevAlert.js';

const API_BASE = '/';
let temporizadorRenovacionSesion = null;
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
  const tokenAcceso = obtenerTokenAcceso();
  if (!tokenAcceso) {
    return null;
  }

  try {
    const partes = tokenAcceso.split('.');
    if (partes.length !== 3) {
      return null;
    }

    return JSON.parse(decodificarBase64Url(partes[1]));
  } catch {
    return null;
  }
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
    rol: localStorage.getItem('user_rol') || usuario.rol || usuario.nombre_rol || 'Estudiante',
  };
}

function sincronizarDatosUsuarioLocal(datosUsuario) {
  if (datosUsuario.nombre) localStorage.setItem('user_nombre', datosUsuario.nombre);
  if (datosUsuario.apellido) localStorage.setItem('user_apellido', datosUsuario.apellido);
  if (datosUsuario.correo) localStorage.setItem('user_email', datosUsuario.correo);
  if (datosUsuario.rol) localStorage.setItem('user_rol', datosUsuario.rol);
  if (datosUsuario.token) localStorage.setItem('token', datosUsuario.token);
}

async function obtenerSesionDesdeRefresh() {
  const response = await fetch(API_BASE + 'refresh', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    },
    credentials: 'include',
    body: JSON.stringify({}),
  });

  if (!response.ok) {
    return null;
  }

  const body = await response.json().catch(() => ({}));
  const data = body.data || body;

  if (!data.access_token) {
    return null;
  }

  const datosUsuario = {
    token: data.access_token,
    nombre: data.user?.nombre || '',
    apellido: data.user?.apellido || '',
    correo: data.user?.correo || '',
    rol: data.user?.rol || data.user?.nombre_rol || 'Estudiante',
  };

  sincronizarDatosUsuarioLocal(datosUsuario);
  return datosUsuario;
}

export function sincronizarSesionDesdeCookies() {
  const datosUsuario = obtenerDatosUsuario();
  if (!datosUsuario.token) {
    return null;
  }

  sincronizarDatosUsuarioLocal(datosUsuario);
  return datosUsuario;
}

async function asegurarDatosUsuario() {
  const datosUsuarioLocal = sincronizarSesionDesdeCookies() || obtenerDatosUsuario();
  const tieneDatosBasicos = Boolean(datosUsuarioLocal.token && (datosUsuarioLocal.nombre || datosUsuarioLocal.apellido || datosUsuarioLocal.correo));

  if (tieneDatosBasicos) {
    return datosUsuarioLocal;
  }

  const datosDesdeRefresh = await obtenerSesionDesdeRefresh();
  return datosDesdeRefresh || datosUsuarioLocal;
}

async function mostrarDatosUsuario() {
  const datosUsuario = await asegurarDatosUsuario();

  if (!datosUsuario.token) {
    limpiarSesionLocalYRedirigir('no_auth');
    return;
  }

  const nombreCompletoUsuario = (datosUsuario.nombre + ' ' + datosUsuario.apellido).trim() || datosUsuario.correo || 'Estudiante';
  const inicialUsuario = (datosUsuario.nombre || datosUsuario.correo || 'E')[0].toUpperCase();

  const elementoNombreEstudiante = document.getElementById('nombreEstudiante');
  const elementoAvatarUsuario = document.getElementById('avatarEstudiante');
  const elementoInformacionUsuario = document.getElementById('infoEstudiante');

  if (elementoNombreEstudiante) {
    elementoNombreEstudiante.textContent = datosUsuario.nombre || nombreCompletoUsuario;
  }

  if (elementoAvatarUsuario) {
    elementoAvatarUsuario.textContent = inicialUsuario;
  }

  if (elementoInformacionUsuario) {
    elementoInformacionUsuario.innerHTML = nombreCompletoUsuario + '<br><small class="text-muted">' + datosUsuario.rol + '</small>';
  }
}

function obtenerExpiracionTokenAcceso() {
  const datosToken = obtenerDatosToken();
  return typeof datosToken?.exp === 'number' ? datosToken.exp * 1000 : null;
}

function limpiarTemporizadorRenovacion() {
  if (temporizadorRenovacionSesion) {
    clearTimeout(temporizadorRenovacionSesion);
    temporizadorRenovacionSesion = null;
  }
}

function limpiarSesionLocalYRedirigir(reason = 'expired') {
  limpiarTemporizadorRenovacion();
  localStorage.clear();

  if (reason) {
    sessionStorage.setItem('redirect_reason', reason);
  }

  window.location.href = '/login';
}

async function renovarSesion() {
  const response = await fetch(API_BASE + 'refresh', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    },
    credentials: 'include',
    body: JSON.stringify({}),
  });

  if (!response.ok) {
    throw new Error('No se pudo renovar la sesión.');
  }

  const body = await response.json().catch(() => ({}));
  const data = body.data || body;

  if (!data.access_token) {
    throw new Error('No se pudo renovar la sesión.');
  }

  localStorage.setItem('token', data.access_token);

  if (data.user) {
    localStorage.setItem('user_rol', data.user.rol || data.user.nombre_rol || 'Estudiante');
    localStorage.setItem('user_nombre', data.user.nombre || '');
    localStorage.setItem('user_apellido', data.user.apellido || '');
    localStorage.setItem('user_email', data.user.correo || '');
  }

  window.dispatchEvent(new CustomEvent('cev:session-refreshed', { detail: data }));
  return true;
}

async function mostrarAvisoRenovacionSesion() {
  const expiracion = obtenerExpiracionTokenAcceso();
  if (!expiracion) {
    limpiarSesionLocalYRedirigir();
    return;
  }

  if (expiracion <= Date.now()) {
    try {
      await renovarSesion();
      programarAvisoRenovacionSesion();
      return;
    } catch {
      limpiarSesionLocalYRedirigir('expired');
      return;
    }
  }

  const resultado = await CevAlert.sessionExpiring({
    title: 'Sesión por expirar',
    html: 'Tu sesión está por vencer. Puedes renovarla para seguir trabajando sin interrupciones.',
    confirmButtonText: 'Renovar sesión',
    cancelButtonText: 'Cerrar sesión',
    timerDuration: 30,
  });

  if (resultado.isConfirmed) {
    try {
      await renovarSesion();
      programarAvisoRenovacionSesion();
      return;
    } catch {
      limpiarSesionLocalYRedirigir('expired');
      return;
    }
  }

  limpiarSesionLocalYRedirigir('expired');
}

function programarAvisoRenovacionSesion() {
  limpiarTemporizadorRenovacion();

  const expiracion = obtenerExpiracionTokenAcceso();
  if (!expiracion) {
    return;
  }

  const ventanaAviso = 60000;
  const tiempoParaAviso = expiracion - Date.now() - ventanaAviso;

  if (tiempoParaAviso <= 0) {
    void mostrarAvisoRenovacionSesion();
    return;
  }

  temporizadorRenovacionSesion = setTimeout(() => {
    void mostrarAvisoRenovacionSesion();
  }, tiempoParaAviso);
}

function inicializarCerrarSesion() {
  const botonCerrarSesion = document.getElementById('btnCerrarSesion');
  if (!botonCerrarSesion) {
    return;
  }

  botonCerrarSesion.addEventListener('click', async (event) => {
    event.preventDefault();

    const confirmacion = await CevAlert.question({
      title: 'Cerrar sesión',
      text: '¿Deseas cerrar sesión ahora?',
      confirmButtonText: 'Sí, cerrar sesión',
      cancelButtonText: 'Cancelar',
    });

    if (!confirmacion.isConfirmed) {
      return;
    }

    try {
      await fetch(API_BASE + 'logout', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer ' + obtenerTokenAcceso(),
        },
        credentials: 'include',
        body: JSON.stringify({}),
      });
    } catch {
      // Se limpia la sesión local aunque falle la petición de cierre.
    }

    limpiarSesionLocalYRedirigir('logout_success');
  });
}

export function initStudentShell() {
  if (inicializado) {
    return;
  }

  inicializado = true;
  inicializarCerrarSesion();
  mostrarDatosUsuario();
  programarAvisoRenovacionSesion();
  window.addEventListener('cev:session-refreshed', programarAvisoRenovacionSesion);
}