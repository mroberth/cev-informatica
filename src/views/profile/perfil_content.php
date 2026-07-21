<div class="cev-perfil-container">
  <div class="cev-perfil-card">
    <div class="cev-perfil-header">
      <h2><i class="bi bi-person-circle"></i> Mi Perfil</h2>
    </div>

    <div class="cev-perfil-avatar-section">
      <div class="cev-perfil-avatar-wrapper">
        <div class="cev-perfil-avatar" id="perfilAvatar"></div>
        <label class="cev-perfil-avatar-overlay" id="avatarUploadLabel">
          <i class="bi bi-camera"></i>
          <input type="file" id="avatarInput" accept="image/png,image/jpeg,image/gif,image/webp" hidden>
        </label>
      </div>
      <div class="invalid-feedback" id="avatarInputError"></div>
      <p class="cev-perfil-avatar-hint">PNG, JPG, GIF o WebP. Máx 2 MB.</p>
    </div>

    <form id="formPerfil" class="cev-perfil-form">
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Nombre</label>
          <input type="text" class="form-control" id="perfilNombre" required>
          <div class="invalid-feedback" id="perfilNombreError"></div>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Apellido</label>
          <input type="text" class="form-control" id="perfilApellido" required>
          <div class="invalid-feedback" id="perfilApellidoError"></div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Correo electrónico</label>
          <input type="email" class="form-control" id="perfilCorreo" required>
          <div class="invalid-feedback" id="perfilCorreoError"></div>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Teléfono</label>
          <input type="tel" class="form-control" id="perfilTelefono" placeholder="04121234567">
          <div class="invalid-feedback" id="perfilTelefonoError"></div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Cédula</label>
          <input type="text" class="form-control" id="perfilCedula" readonly disabled>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Rol</label>
          <input type="text" class="form-control" id="perfilRol" readonly disabled>
        </div>
      </div>
      <button type="submit" class="cev-perfil-btn cev-perfil-btn-primary" id="btnGuardarPerfil">
        <i class="bi bi-check-lg"></i> Guardar cambios
      </button>
    </form>
  </div>

  <div class="cev-perfil-card">
    <div class="cev-perfil-header">
      <h3><i class="bi bi-shield-lock"></i> Cambiar contraseña</h3>
    </div>
    <form id="formPassword" class="cev-perfil-form">
      <div class="row">
        <div class="col-md-4 mb-3">
          <label class="form-label">Contraseña actual</label>
          <input type="password" class="form-control" id="passActual" required>
          <div class="invalid-feedback" id="passActualError"></div>
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Nueva contraseña</label>
          <input type="password" class="form-control" id="passNueva" required minlength="8">
          <div class="invalid-feedback" id="passNuevaError"></div>
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Confirmar nueva contraseña</label>
          <input type="password" class="form-control" id="passConfirmar" required>
          <div class="invalid-feedback" id="passConfirmarError"></div>
        </div>
      </div>
      <button type="submit" class="cev-perfil-btn cev-perfil-btn-secondary" id="btnGuardarPassword">
        <i class="bi bi-check-lg"></i> Cambiar contraseña
      </button>
    </form>
  </div>
</div>
