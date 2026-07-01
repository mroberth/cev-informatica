import { CevAlert } from '/js/utils/CevAlert.js';
import { apiClient } from '/js/api/client.js';

const API_BASE = '/';
let temporizadorRenovacionSesion = null;
let inicializado = false;

function obtenerTokenAcceso() {
  return localStorage.getItem('token');
}

function obtenerDatosToken() {
  const tokenAcceso = obtenerTokenAcceso();
  if (!tokenAcceso) {
    return null;
  }

  try {
    return JSON.parse(atob(tokenAcceso.split('.')[1]));
  } catch {
    return null;
  }
}

function mostrarDatosUsuario() {
  const datosToken = obtenerDatosToken();
  if (!datosToken) {
    limpiarSesionLocalYRedirigir('no_auth');
    return;
  }

  const nombreUsuario = localStorage.getItem('user_nombre') || '';
  const apellidoUsuario = localStorage.getItem('user_apellido') || '';
  const rolUsuario = datosToken.user?.rol || localStorage.getItem('user_rol') || 'Usuario';
  const nombreCompletoUsuario = (nombreUsuario + ' ' + apellidoUsuario).trim() || 'Usuario';
  const inicialUsuario = (nombreUsuario || 'U')[0].toUpperCase();

  const elementoRolUsuario = document.getElementById('textoRolUsuario');
  const elementoNombreCompleto = document.getElementById('nombreCompletoUsuario');
  const elementoNombreCabecera = document.getElementById('nombreUsuarioCabecera');
  const elementoAvatarUsuario = document.getElementById('avatarUsuario');
  const elementoInformacionUsuario = document.getElementById('informacionUsuarioDesplegable');

  if (elementoRolUsuario) elementoRolUsuario.textContent = ', ' + rolUsuario;
  if (elementoNombreCompleto) elementoNombreCompleto.textContent = nombreCompletoUsuario;
  if (elementoNombreCabecera) elementoNombreCabecera.textContent = nombreCompletoUsuario;
  if (elementoAvatarUsuario) elementoAvatarUsuario.textContent = inicialUsuario;
  if (elementoInformacionUsuario) {
    elementoInformacionUsuario.innerHTML = nombreCompletoUsuario + '<br><small class="text-muted">' + rolUsuario + '</small>';
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
  const response = await apiClient.post('/refresh', {});
  const data = response.data || {};

  if (!data.access_token) {
    throw new Error('No se pudo renovar la sesión.');
  }

  localStorage.setItem('token', data.access_token);

  if (data.user) {
    localStorage.setItem('user_rol', data.user.rol || data.user.nombre_rol || 'Usuario');
    localStorage.setItem('user_nombre', data.user.nombre || '');
    localStorage.setItem('user_apellido', data.user.apellido || '');
    localStorage.setItem('user_email', data.user.correo || '');
  }

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

function inicializarSidebar() {
  const botonMenuSidebar = document.getElementById('botonMenuSidebar');
  const sidebar = document.getElementById('sidebar');

  if (botonMenuSidebar && sidebar) {
    botonMenuSidebar.addEventListener('click', () => {
      sidebar.classList.toggle('open');
    });
  }
}

function inicializarCerrarSesion() {
  const botonCerrarSesion = document.getElementById('botonCerrarSesion');
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
          'Authorization': 'Bearer ' + obtenerTokenAcceso()
        },
        credentials: 'include',
        body: JSON.stringify({})
      });
    } catch {
      // Se limpia la sesión local aunque falle la petición de cierre.
    }

    limpiarSesionLocalYRedirigir('logout_success');
  });
}

export function initAdminShell() {
  if (inicializado) {
    return;
  }

  inicializado = true;
  inicializarSidebar();
  inicializarCerrarSesion();
  mostrarDatosUsuario();
  programarAvisoRenovacionSesion();
  window.addEventListener('cev:session-refreshed', programarAvisoRenovacionSesion);
}
