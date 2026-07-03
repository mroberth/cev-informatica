<?php
use App\Configuracion\Repository\ConfigRepository;
use App\Configuracion\Service\ConfigService;

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

    try {
        $service = new ConfigService();
        $repositorio = new ConfigRepository();

        $rolDTO = $service->validarRol(
            (string) ($input['nombre_rol'] ?? ''),
            (string) ($input['descripcion'] ?? '')
        );

        if ($repositorio->rolExiste($rolDTO->getNombreRol())) {
            http_response_code(400);
            echo json_encode(['error' => 'Ya existe un rol con ese nombre.']);
            return;
        }

        if ($repositorio->crearRol($rolDTO->getNombreRol(), $rolDTO->getDescripcion())) {
            registrar_en_bitacora('CREAR', "Rol creado: {$rolDTO->getNombreRol()}");
            echo json_encode(['status' => 'success', 'message' => 'Rol creado correctamente.']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'No se pudo crear el rol.']);
        }
    } catch (Exception $e) {
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode(['error' => $e->getMessage()]);
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

    try {
        $service = new ConfigService();
        $repositorio = new ConfigRepository();

        $rolDTO = $service->validarRolExistente(
            (int) ($input['id'] ?? 0),
            (string) ($input['nombre_rol'] ?? ''),
            (string) ($input['descripcion'] ?? '')
        );

        if ($repositorio->rolExiste($rolDTO->getNombreRol(), $rolDTO->getId())) {
            http_response_code(400);
            echo json_encode(['error' => 'Ya existe otro rol con ese nombre.']);
            return;
        }

        if ($repositorio->actualizarRol($rolDTO->getId(), $rolDTO->getNombreRol(), $rolDTO->getDescripcion())) {
            registrar_en_bitacora('ACTUALIZAR', "Rol actualizado: {$rolDTO->getNombreRol()} (ID: {$rolDTO->getId()})");
            echo json_encode(['status' => 'success', 'message' => 'Rol actualizado correctamente.']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'No se pudo actualizar el rol.']);
        }
    } catch (Exception $e) {
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode(['error' => $e->getMessage()]);
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

    try {
        $service = new ConfigService();
        $repositorio = new ConfigRepository();

        $moduloDTO = $service->validarModulo(
            (string) ($input['nombre'] ?? ''),
            (string) ($input['descripcion'] ?? '')
        );

        if ($repositorio->moduloExiste($moduloDTO->getNombre())) {
            http_response_code(400);
            echo json_encode(['error' => 'Ya existe un módulo con ese nombre.']);
            return;
        }

        if ($repositorio->crearModulo($moduloDTO->getNombre(), $moduloDTO->getDescripcion())) {
            registrar_en_bitacora('CREAR', "Módulo creado: {$moduloDTO->getNombre()}");
            echo json_encode(['status' => 'success', 'message' => 'Módulo creado correctamente.']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'No se pudo crear el módulo.']);
        }
    } catch (Exception $e) {
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode(['error' => $e->getMessage()]);
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

    try {
        $service = new ConfigService();
        $repositorio = new ConfigRepository();

        $moduloDTO = $service->validarModuloExistente(
            (int) ($input['id'] ?? 0),
            (string) ($input['nombre'] ?? ''),
            (string) ($input['descripcion'] ?? '')
        );

        if ($repositorio->moduloExiste($moduloDTO->getNombre(), $moduloDTO->getId())) {
            http_response_code(400);
            echo json_encode(['error' => 'Ya existe otro módulo con ese nombre.']);
            return;
        }

        if ($repositorio->actualizarModulo($moduloDTO->getId(), $moduloDTO->getNombre(), $moduloDTO->getDescripcion())) {
            registrar_en_bitacora('ACTUALIZAR', "Módulo actualizado: {$moduloDTO->getNombre()} (ID: {$moduloDTO->getId()})");
            echo json_encode(['status' => 'success', 'message' => 'Módulo actualizado correctamente.']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'No se pudo actualizar el módulo.']);
        }
    } catch (Exception $e) {
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode(['error' => $e->getMessage()]);
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

    try {
        $service = new ConfigService();
        $repositorio = new ConfigRepository();

        $permisoDTO = $service->validarPermiso(
            (string) ($input['nombre'] ?? ''),
            (string) ($input['descripcion'] ?? '')
        );

        if ($repositorio->permisoExiste($permisoDTO->getNombre())) {
            http_response_code(400);
            echo json_encode(['error' => 'Ya existe un permiso con ese nombre.']);
            return;
        }

        if ($repositorio->crearPermiso($permisoDTO->getNombre(), $permisoDTO->getDescripcion())) {
            registrar_en_bitacora('CREAR', "Permiso creado: {$permisoDTO->getNombre()}");
            echo json_encode(['status' => 'success', 'message' => 'Permiso creado correctamente.']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'No se pudo crear el permiso.']);
        }
    } catch (Exception $e) {
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode(['error' => $e->getMessage()]);
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

    try {
        $service = new ConfigService();
        $repositorio = new ConfigRepository();

        $permisoDTO = $service->validarPermisoExistente(
            (int) ($input['id'] ?? 0),
            (string) ($input['nombre'] ?? ''),
            (string) ($input['descripcion'] ?? '')
        );

        if ($repositorio->permisoExiste($permisoDTO->getNombre(), $permisoDTO->getId())) {
            http_response_code(400);
            echo json_encode(['error' => 'Ya existe otro permiso con ese nombre.']);
            return;
        }

        if ($repositorio->actualizarPermiso($permisoDTO->getId(), $permisoDTO->getNombre(), $permisoDTO->getDescripcion())) {
            registrar_en_bitacora('ACTUALIZAR', "Permiso actualizado: {$permisoDTO->getNombre()} (ID: {$permisoDTO->getId()})");
            echo json_encode(['status' => 'success', 'message' => 'Permiso actualizado correctamente.']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'No se pudo actualizar el permiso.']);
        }
    } catch (Exception $e) {
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}
