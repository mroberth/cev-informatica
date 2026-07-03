<?php
use App\Configuracion\Repository\ConfigRepository;

function configuracion(): void{
    require_once BASE_PATH . "/src/views/config/crear_config.php";
}

function consultar_configuracion(): void{
    require_once BASE_PATH . "/src/views/config/consultar_config.php";
}

// ==================== VERIFICAR DUPLICADOS ====================
function verificar_rol(): void {
    header('Content-Type: application/json; charset=utf-8');
    $nombre = trim($_GET['nombre'] ?? '');
    $idExcluir = isset($_GET['id_excluir']) ? (int) $_GET['id_excluir'] : null;
    $repositorio = new ConfigRepository();
    echo json_encode(['existe' => $repositorio->rolExiste($nombre, $idExcluir)]);
    exit;
}

function verificar_modulo(): void {
    header('Content-Type: application/json; charset=utf-8');
    $nombre = trim($_GET['nombre'] ?? '');
    $idExcluir = isset($_GET['id_excluir']) ? (int) $_GET['id_excluir'] : null;
    $repositorio = new ConfigRepository();
    echo json_encode(['existe' => $repositorio->moduloExiste($nombre, $idExcluir)]);
    exit;
}

function verificar_permiso(): void {
    header('Content-Type: application/json; charset=utf-8');
    $nombre = trim($_GET['nombre'] ?? '');
    $idExcluir = isset($_GET['id_excluir']) ? (int) $_GET['id_excluir'] : null;
    $repositorio = new ConfigRepository();
    echo json_encode(['existe' => $repositorio->permisoExiste($nombre, $idExcluir)]);
    exit;
}

// ==================== ROLES ====================
function obtener_roles_config(): void {
    header('Content-Type: application/json; charset=utf-8');
    $repositorio = new ConfigRepository();
    $roles = $repositorio->obtenerRoles();
    echo json_encode(['data' => $roles]);
    exit;
}

function guardar_rol(): void {
    header('Content-Type: application/json; charset=utf-8');
    $input = json_decode(file_get_contents('php://input'), true);

    if (!is_array($input)) {
        http_response_code(400);
        echo json_encode(['error' => 'Datos inválidos.']);
        return;
    }

    $nombre = trim((string) ($input['nombre_rol'] ?? ''));
    $descripcion = trim((string) ($input['descripcion'] ?? ''));

    $repositorio = new ConfigRepository();

    if ($repositorio->rolExiste($nombre)) {
        http_response_code(400);
        echo json_encode(['error' => 'Ya existe un rol con ese nombre.']);
        return;
    }

    if ($repositorio->crearRol($nombre, $descripcion ?: null)) {
        registrar_en_bitacora('CREAR', "Rol creado: {$nombre}");
        echo json_encode(['status' => 'success', 'message' => 'Rol creado correctamente.']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'No se pudo crear el rol.']);
    }
    exit;
}

function actualizar_rol(): void {
    header('Content-Type: application/json; charset=utf-8');
    $input = json_decode(file_get_contents('php://input'), true);

    if (!is_array($input)) {
        http_response_code(400);
        echo json_encode(['error' => 'Datos inválidos.']);
        return;
    }

    $id = (int) ($input['id'] ?? 0);
    $nombre = trim((string) ($input['nombre_rol'] ?? ''));
    $descripcion = trim((string) ($input['descripcion'] ?? ''));

    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'ID inválido.']);
        return;
    }

    $repositorio = new ConfigRepository();

    if ($repositorio->rolExiste($nombre, $id)) {
        http_response_code(400);
        echo json_encode(['error' => 'Ya existe otro rol con ese nombre.']);
        return;
    }

    if ($repositorio->actualizarRol($id, $nombre, $descripcion ?: null)) {
        registrar_en_bitacora('ACTUALIZAR', "Rol actualizado: {$nombre} (ID: {$id})");
        echo json_encode(['status' => 'success', 'message' => 'Rol actualizado correctamente.']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'No se pudo actualizar el rol.']);
    }
    exit;
}

// ==================== MODULOS ====================
function obtener_modulos_config(): void {
    header('Content-Type: application/json; charset=utf-8');
    $repositorio = new ConfigRepository();
    $modulos = $repositorio->obtenerModulos();
    echo json_encode(['data' => $modulos]);
    exit;
}

function guardar_modulo(): void {
    header('Content-Type: application/json; charset=utf-8');
    $input = json_decode(file_get_contents('php://input'), true);

    if (!is_array($input)) {
        http_response_code(400);
        echo json_encode(['error' => 'Datos inválidos.']);
        return;
    }

    $nombre = trim((string) ($input['nombre'] ?? ''));
    $descripcion = trim((string) ($input['descripcion'] ?? ''));

    $repositorio = new ConfigRepository();

    if ($repositorio->moduloExiste($nombre)) {
        http_response_code(400);
        echo json_encode(['error' => 'Ya existe un módulo con ese nombre.']);
        return;
    }

    if ($repositorio->crearModulo($nombre, $descripcion ?: null)) {
        registrar_en_bitacora('CREAR', "Módulo creado: {$nombre}");
        echo json_encode(['status' => 'success', 'message' => 'Módulo creado correctamente.']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'No se pudo crear el módulo.']);
    }
    exit;
}

function actualizar_modulo(): void {
    header('Content-Type: application/json; charset=utf-8');
    $input = json_decode(file_get_contents('php://input'), true);

    if (!is_array($input)) {
        http_response_code(400);
        echo json_encode(['error' => 'Datos inválidos.']);
        return;
    }

    $id = (int) ($input['id'] ?? 0);
    $nombre = trim((string) ($input['nombre'] ?? ''));
    $descripcion = trim((string) ($input['descripcion'] ?? ''));

    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'ID inválido.']);
        return;
    }

    $repositorio = new ConfigRepository();

    if ($repositorio->moduloExiste($nombre, $id)) {
        http_response_code(400);
        echo json_encode(['error' => 'Ya existe otro módulo con ese nombre.']);
        return;
    }

    if ($repositorio->actualizarModulo($id, $nombre, $descripcion ?: null)) {
        registrar_en_bitacora('ACTUALIZAR', "Módulo actualizado: {$nombre} (ID: {$id})");
        echo json_encode(['status' => 'success', 'message' => 'Módulo actualizado correctamente.']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'No se pudo actualizar el módulo.']);
    }
    exit;
}

// ==================== PERMISOS ====================
function obtener_permisos_config(): void {
    header('Content-Type: application/json; charset=utf-8');
    $repositorio = new ConfigRepository();
    $permisos = $repositorio->obtenerPermisos();
    echo json_encode(['data' => $permisos]);
    exit;
}

function guardar_permiso(): void {
    header('Content-Type: application/json; charset=utf-8');
    $input = json_decode(file_get_contents('php://input'), true);

    if (!is_array($input)) {
        http_response_code(400);
        echo json_encode(['error' => 'Datos inválidos.']);
        return;
    }

    $nombre = trim((string) ($input['nombre'] ?? ''));
    $descripcion = trim((string) ($input['descripcion'] ?? ''));

    $repositorio = new ConfigRepository();

    if ($repositorio->permisoExiste($nombre)) {
        http_response_code(400);
        echo json_encode(['error' => 'Ya existe un permiso con ese nombre.']);
        return;
    }

    if ($repositorio->crearPermiso($nombre, $descripcion ?: null)) {
        registrar_en_bitacora('CREAR', "Permiso creado: {$nombre}");
        echo json_encode(['status' => 'success', 'message' => 'Permiso creado correctamente.']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'No se pudo crear el permiso.']);
    }
    exit;
}

function actualizar_permiso(): void {
    header('Content-Type: application/json; charset=utf-8');
    $input = json_decode(file_get_contents('php://input'), true);

    if (!is_array($input)) {
        http_response_code(400);
        echo json_encode(['error' => 'Datos inválidos.']);
        return;
    }

    $id = (int) ($input['id'] ?? 0);
    $nombre = trim((string) ($input['nombre'] ?? ''));
    $descripcion = trim((string) ($input['descripcion'] ?? ''));

    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'ID inválido.']);
        return;
    }

    $repositorio = new ConfigRepository();

    if ($repositorio->permisoExiste($nombre, $id)) {
        http_response_code(400);
        echo json_encode(['error' => 'Ya existe otro permiso con ese nombre.']);
        return;
    }

    if ($repositorio->actualizarPermiso($id, $nombre, $descripcion ?: null)) {
        registrar_en_bitacora('ACTUALIZAR', "Permiso actualizado: {$nombre} (ID: {$id})");
        echo json_encode(['status' => 'success', 'message' => 'Permiso actualizado correctamente.']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'No se pudo actualizar el permiso.']);
    }
    exit;
}
