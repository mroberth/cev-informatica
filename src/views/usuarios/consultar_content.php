<main class="main-content">
  <div class="mb-4 d-flex flex-wrap align-items-center justify-content-between gap-2">
    <div>
      <h4 class="mb-1"><i class="bi bi-search me-2"></i>Consultar Usuarios</h4>
      <p class="text-muted mb-0">Listado general de usuarios registrados en el sistema</p>
    </div>
    <a href="/a/usuarios/crear" class="btn text-white fw-semibold" style="background: #1a2a4a;">
      <i class="bi bi-plus-lg me-1"></i>Nuevo usuario
    </a>
  </div>

  <div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-3 p-md-4">
      <div class="table-responsive">
        <table id="tablaUsuarios" class="table table-hover align-middle" style="width:100%">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Apellido</th>
              <th>Correo</th>
              <th>Rol</th>
              <th>Estado</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</main>
