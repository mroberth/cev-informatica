const showError = (field, msg) => {
  const errorElement = document.getElementById(`${field.id}Error`);
  if (errorElement) errorElement.textContent = msg;
  field.classList.add('is-invalid');
  field.classList.remove('is-valid');
};

const clearError = (field) => {
  const errorElement = document.getElementById(`${field.id}Error`);
  if (errorElement) errorElement.textContent = '';
  field.classList.remove('is-invalid');
  field.classList.add('is-valid');
};

function obtenerToken() {
  return localStorage.getItem('token');
}

function getUserId() {
  const token = obtenerToken();
  if (!token) return null;
  try {
    const payload = JSON.parse(atob(token.split('.')[1]));
    return payload.sub || null;
  } catch { return null; }
}

function inicializarAvatar(nombre, apellido, avatarUrl) {
  const container = document.getElementById('perfilAvatar');
  if (!container) return;
  if (avatarUrl) {
    container.innerHTML = `<img src="${avatarUrl}" alt="Avatar">`;
  } else {
    const inicial = (nombre?.charAt(0) || '?').toUpperCase();
    container.textContent = inicial;
  }
}

function cargarPerfil() {
  const token = obtenerToken();
  if (!token) return;
  fetch('/perfil/data', {
    headers: { 'Authorization': `Bearer ${token}` }
  })
    .then(res => res.json())
    .then(json => {
      if (!json.data) return;
      const u = json.data;
      document.getElementById('perfilNombre').value = u.nombre || '';
      document.getElementById('perfilApellido').value = u.apellido || '';
      document.getElementById('perfilCorreo').value = u.correo || '';
      document.getElementById('perfilTelefono').value = u.telefono || '';
      document.getElementById('perfilCedula').value = (u.tipo_cedula || '') + (u.cedula || '');
      document.getElementById('perfilRol').value = u.rol || '';
      inicializarAvatar(u.nombre, u.apellido, u.avatar_url);
    })
    .catch(() => {});
}

function validarNombre() {
  const field = document.getElementById('perfilNombre');
  if (!field.value.trim()) { showError(field, 'El nombre es obligatorio.'); return false; }
  clearError(field); return true;
}

function validarApellido() {
  const field = document.getElementById('perfilApellido');
  if (!field.value.trim()) { showError(field, 'El apellido es obligatorio.'); return false; }
  clearError(field); return true;
}

function validarCorreo() {
  const field = document.getElementById('perfilCorreo');
  const val = field.value.trim();
  if (!val) { showError(field, 'El correo es obligatorio.'); return false; }
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) { showError(field, 'El correo no es válido.'); return false; }
  clearError(field); return true;
}

function validarTelefono() {
  const field = document.getElementById('perfilTelefono');
  const val = field.value.trim();
  if (val && !/^0\d{10}$/.test(val)) { showError(field, 'Debe tener 11 dígitos empezando con 0.'); return false; }
  clearError(field); return true;
}

async function guardarPerfil(e) {
  e.preventDefault();
  const validaciones = [validarNombre(), validarApellido(), validarCorreo(), validarTelefono()];
  if (!validaciones.every(v => v)) return;

  try {
    const token = obtenerToken();
    const res = await fetch('/perfil/update', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', ...(token ? { 'Authorization': `Bearer ${token}` } : {}) },
      body: JSON.stringify({
        nombre: document.getElementById('perfilNombre').value.trim(),
        apellido: document.getElementById('perfilApellido').value.trim(),
        correo: document.getElementById('perfilCorreo').value.trim(),
        telefono: document.getElementById('perfilTelefono').value.trim(),
      }),
    });
    const json = await res.json();
    if (!res.ok) { alert(json.error || 'Error al guardar.'); return; }
    alert('Perfil actualizado correctamente.');
  } catch { alert('Error de conexión.'); }
}

function validarPassActual() {
  const field = document.getElementById('passActual');
  if (!field.value) { showError(field, 'Debes ingresar tu contraseña actual.'); return false; }
  clearError(field); return true;
}

function validarPassNueva() {
  const field = document.getElementById('passNueva');
  if (!field.value) { showError(field, 'La nueva contraseña es obligatoria.'); return false; }
  if (field.value.length < 8) { showError(field, 'Debe tener al menos 8 caracteres.'); return false; }
  clearError(field); return true;
}

function validarPassConfirmar() {
  const field = document.getElementById('passConfirmar');
  const nueva = document.getElementById('passNueva').value;
  if (!field.value) { showError(field, 'Debes confirmar la contraseña.'); return false; }
  if (field.value !== nueva) { showError(field, 'Las contraseñas no coinciden.'); return false; }
  clearError(field); return true;
}

async function guardarPassword(e) {
  e.preventDefault();
  const validaciones = [validarPassActual(), validarPassNueva(), validarPassConfirmar()];
  if (!validaciones.every(v => v)) return;

  try {
    const token = obtenerToken();
    const res = await fetch('/perfil/password', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', ...(token ? { 'Authorization': `Bearer ${token}` } : {}) },
      body: JSON.stringify({
        current_password: document.getElementById('passActual').value,
        new_password: document.getElementById('passNueva').value,
        confirm_password: document.getElementById('passConfirmar').value,
      }),
    });
    const json = await res.json();
    if (!res.ok) { alert(json.error || 'Error al cambiar contraseña.'); return; }
    alert('Contraseña actualizada correctamente.');
    document.getElementById('formPassword').reset();
    document.querySelectorAll('#formPassword .is-valid').forEach(el => el.classList.remove('is-valid'));
  } catch { alert('Error de conexión.'); }
}

async function uploadAvatar(file) {
  const formData = new FormData();
  formData.append('avatar', file);

  const errorEl = document.getElementById('avatarInputError');
  const input = document.getElementById('avatarInput');

  const ext = file.name.split('.').pop()?.toLowerCase();
  const allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

  if (!allowed.includes(ext)) {
    errorEl.textContent = 'Tipo de imagen no permitido.';
    input.classList.add('is-invalid');
    return;
  }
  if (file.size > 2 * 1024 * 1024) {
    errorEl.textContent = 'La imagen supera los 2 MB.';
    input.classList.add('is-invalid');
    return;
  }

  errorEl.textContent = '';
  input.classList.remove('is-invalid');

  try {
    const token = obtenerToken();
    const res = await fetch('/perfil/avatar', {
      method: 'POST',
      headers: token ? { 'Authorization': `Bearer ${token}` } : {},
      body: formData,
    });
    const json = await res.json();
    if (!res.ok) { alert(json.error || 'Error al subir avatar.'); return; }

    const container = document.getElementById('perfilAvatar');
    container.innerHTML = `<img src="${json.avatar_url}?t=${Date.now()}" alt="Avatar">`;
    localStorage.setItem('user_avatar', json.avatar_url);
    alert('Avatar actualizado.');
  } catch { alert('Error de conexión.'); }
}

function initPerfil() {
  cargarPerfil();

  document.getElementById('formPerfil')?.addEventListener('submit', guardarPerfil);
  document.getElementById('formPassword')?.addEventListener('submit', guardarPassword);
  document.getElementById('avatarInput')?.addEventListener('change', (e) => {
    if (e.target.files?.[0]) uploadAvatar(e.target.files[0]);
  });

  document.getElementById('perfilNombre')?.addEventListener('blur', validarNombre);
  document.getElementById('perfilApellido')?.addEventListener('blur', validarApellido);
  document.getElementById('perfilCorreo')?.addEventListener('blur', validarCorreo);
  document.getElementById('perfilTelefono')?.addEventListener('blur', validarTelefono);
  document.getElementById('passActual')?.addEventListener('blur', validarPassActual);
  document.getElementById('passNueva')?.addEventListener('blur', validarPassNueva);
  document.getElementById('passConfirmar')?.addEventListener('blur', validarPassConfirmar);
}

document.addEventListener('DOMContentLoaded', initPerfil);
