<?php

use Core\Database\Conexion;
use Core\Middleware\AuthMiddleware;

function profile_show(): void
{
    require_once BASE_PATH . '/src/views/profile/perfil.php';
}

function profile_data(): void
{
    header('Content-Type: application/json; charset=utf-8');

    try {
        $payload = AuthMiddleware::getUsuarioPayload();
        $idUsuario = (int) ($payload['sub'] ?? 0);
        if ($idUsuario <= 0) throw new Exception('No autenticado', 401);

        $pdo = Conexion::obtenerConexion('security');
        $stmt = $pdo->prepare(
            "SELECT u.id, u.tipo_cedula, u.cedula, u.nombre, u.apellido, u.correo, u.telefono, u.avatar_url,
                    r.nombre_rol AS rol
             FROM usuarios u
             JOIN roles r ON u.rol_id = r.id
             WHERE u.id = :id"
        );
        $stmt->bindValue(':id', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) throw new Exception('Usuario no encontrado.', 404);

        echo json_encode(['data' => $usuario], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode(['status' => 'error', 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function profile_update(): void
{
    header('Content-Type: application/json; charset=utf-8');

    try {
        $payload = AuthMiddleware::getUsuarioPayload();
        $idUsuario = (int) ($payload['sub'] ?? 0);
        if ($idUsuario <= 0) throw new Exception('No autenticado', 401);

        $input = json_decode(file_get_contents('php://input'), true);
        if (!is_array($input)) throw new Exception('Datos inválidos.', 400);

        $nombre = trim($input['nombre'] ?? '');
        $apellido = trim($input['apellido'] ?? '');
        $correo = trim($input['correo'] ?? '');
        $telefono = trim($input['telefono'] ?? '');

        if (empty($nombre)) throw new Exception('El nombre es obligatorio.', 422);
        if (empty($apellido)) throw new Exception('El apellido es obligatorio.', 422);
        if (empty($correo)) throw new Exception('El correo es obligatorio.', 422);
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) throw new Exception('El correo no es válido.', 422);

        $pdo = Conexion::obtenerConexion('security');
        $stmt = $pdo->prepare(
            "UPDATE usuarios SET nombre = :nombre, apellido = :apellido, correo = :correo, telefono = :telefono
             WHERE id = :id"
        );
        $stmt->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindValue(':apellido', $apellido, PDO::PARAM_STR);
        $stmt->bindValue(':correo', $correo, PDO::PARAM_STR);
        $stmt->bindValue(':telefono', $telefono ?: null, PDO::PARAM_STR);
        $stmt->bindValue(':id', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode(['status' => 'ok', 'message' => 'Perfil actualizado correctamente.'], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode(['status' => 'error', 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function profile_password(): void
{
    header('Content-Type: application/json; charset=utf-8');

    try {
        $payload = AuthMiddleware::getUsuarioPayload();
        $idUsuario = (int) ($payload['sub'] ?? 0);
        if ($idUsuario <= 0) throw new Exception('No autenticado', 401);

        $input = json_decode(file_get_contents('php://input'), true);
        if (!is_array($input)) throw new Exception('Datos inválidos.', 400);

        $current = $input['current_password'] ?? '';
        $newPass = $input['new_password'] ?? '';
        $confirm = $input['confirm_password'] ?? '';

        if (empty($current)) throw new Exception('Debes ingresar tu contraseña actual.', 422);
        if (empty($newPass)) throw new Exception('La nueva contraseña es obligatoria.', 422);
        if (strlen($newPass) < 8) throw new Exception('La nueva contraseña debe tener al menos 8 caracteres.', 422);
        if ($newPass !== $confirm) throw new Exception('Las contraseñas nuevas no coinciden.', 422);

        $pdo = Conexion::obtenerConexion('security');
        $stmt = $pdo->prepare("SELECT password_hash FROM usuarios WHERE id = :id");
        $stmt->bindValue(':id', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row || !password_verify($current, $row['password_hash'])) {
            throw new Exception('La contraseña actual es incorrecta.', 422);
        }

        $hash = password_hash($newPass, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE usuarios SET password_hash = :hash WHERE id = :id");
        $stmt->bindValue(':hash', $hash, PDO::PARAM_STR);
        $stmt->bindValue(':id', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode(['status' => 'ok', 'message' => 'Contraseña actualizada correctamente.'], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode(['status' => 'error', 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function profile_avatar(): void
{
    header('Content-Type: application/json; charset=utf-8');

    try {
        $payload = AuthMiddleware::getUsuarioPayload();
        $idUsuario = (int) ($payload['sub'] ?? 0);
        if ($idUsuario <= 0) throw new Exception('No autenticado', 401);

        if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Debes seleccionar una imagen.', 400);
        }

        $file = $_FILES['avatar'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array($ext, $allowed)) throw new Exception('Tipo de imagen no permitido. Extensiones: ' . implode(', ', $allowed), 400);
        if ($file['size'] > 2 * 1024 * 1024) throw new Exception('La imagen supera los 2 MB.', 400);

        $uploadDir = BASE_PATH . '/public/uploads/avatars/' . $idUsuario . '/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0775, true);

        $filename = 'avatar_' . time() . '.' . $ext;
        $destino = $uploadDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destino)) {
            throw new Exception('Error al guardar la imagen.', 500);
        }

        $ruta = '/uploads/avatars/' . $idUsuario . '/' . $filename;

        $pdo = Conexion::obtenerConexion('security');
        $stmt = $pdo->prepare("UPDATE usuarios SET avatar_url = :avatar WHERE id = :id");
        $stmt->bindValue(':avatar', $ruta, PDO::PARAM_STR);
        $stmt->bindValue(':id', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode(['status' => 'ok', 'avatar_url' => $ruta, 'message' => 'Avatar actualizado correctamente.'], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode(['status' => 'error', 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}
