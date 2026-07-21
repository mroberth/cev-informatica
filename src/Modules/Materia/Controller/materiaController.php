<?php

use App\Materia\DTO\CalificacionDTO;
use App\Materia\DTO\EntregaDTO;
use App\Materia\DTO\EvaluacionDTO;
use App\Materia\Repository\MateriaRepository;
use App\Materia\Service\MateriaService;
use App\Notificacion\Repository\NotificacionRepository;
use Core\Middleware\AuthMiddleware;

function extraerIdUrl(): int
{
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $segments = explode('/', trim($uri, '/'));
    foreach (array_reverse($segments) as $seg) {
        if (ctype_digit($seg)) return (int) $seg;
    }
    return 0;
}

function student_materia(): void
{
    $materiaId = extraerIdUrl();
    if ($materiaId <= 0) {
        responder_error(404);
        return;
    }

    $payload = AuthMiddleware::getUsuarioPayload();
    $idUsuario = (int) ($payload['sub'] ?? 0);

    $repository = new MateriaRepository();
    $asignacion = $repository->obtenerAsignacionEstudiante($idUsuario, $materiaId);

    if (!$asignacion) {
        responder_error(404);
        return;
    }

    $_ENV['__MATERIA_INFO'] = $asignacion;
    require_once BASE_PATH . '/src/views/student/materia.php';
}

function student_materia_recursos(): void
{
    header('Content-Type: application/json; charset=utf-8');

    try {
        $materiaId = extraerIdUrl();
        if ($materiaId <= 0) {
            throw new Exception('ID de materia inválido.', 400);
        }

        $payload = AuthMiddleware::getUsuarioPayload();
        $idUsuario = (int) ($payload['sub'] ?? 0);

        $repository = new MateriaRepository();
        $asignacion = $repository->obtenerAsignacionEstudiante($idUsuario, $materiaId);

        if (!$asignacion) {
            throw new Exception('Materia no encontrada.', 404);
        }

        $recursos = $repository->obtenerRecursos((int) $asignacion['id']);

        $recursosMapeados = array_map(function ($r) {
            return [
                'id' => (int) $r['id'],
                'titulo' => $r['titulo'],
                'descripcion' => $r['descripcion'],
                'tipo' => $r['tipo'],
                'archivo_ruta' => $r['archivo_ruta'],
                'enlace_url' => $r['enlace_url'],
                'creado_por' => (int) $r['creado_por'],
                'creado_por_nombre' => trim(($r['creador_nombre'] ?? '') . ' ' . ($r['creador_apellido'] ?? '')),
                'creado_en' => $r['creado_en'],
            ];
        }, $recursos);

        echo json_encode([
            'data' => $recursosMapeados,
            'materia' => [
                'id' => (int) $asignacion['id_unidad_curricular'],
                'nombre' => $asignacion['uc_nombre'],
                'codigo' => $asignacion['codigo'],
                'trayecto' => $asignacion['trayecto'],
                'seccion' => $asignacion['codigo_seccion'],
            ]
        ], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode([
            'status' => 'error',
            'error' => $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function student_materia_evaluaciones(): void
{
    header('Content-Type: application/json; charset=utf-8');

    try {
        $materiaId = extraerIdUrl();
        if ($materiaId <= 0) {
            throw new Exception('ID de materia inválido.', 400);
        }

        $payload = AuthMiddleware::getUsuarioPayload();
        $idUsuario = (int) ($payload['sub'] ?? 0);

        $repository = new MateriaRepository();
        $asignacion = $repository->obtenerAsignacionEstudiante($idUsuario, $materiaId);

        if (!$asignacion) {
            throw new Exception('Materia no encontrada.', 404);
        }

        $evaluaciones = $repository->obtenerEvaluaciones((int) $asignacion['id']);

        $colores = [
            'tarea' => '#0d6efd',
            'examen' => '#dc3545',
            'proyecto' => '#198754',
            'taller' => '#fd7e14',
            'otro' => '#6f42c1',
        ];

        $evaluacionesMapeadas = array_map(function ($e) use ($colores, $repository, $idUsuario) {
            $entrega = $repository->obtenerEntregaEstudiante((int) $e['id'], $idUsuario);
            return [
                'id' => (int) $e['id'],
                'titulo' => $e['titulo'],
                'descripcion' => $e['descripcion'],
                'tipo' => $e['tipo'],
                'porcentaje' => $e['porcentaje'] ? (float) $e['porcentaje'] : null,
                'fecha_estimada' => $e['fecha_estimada'],
                'color' => $colores[$e['tipo']] ?? $colores['otro'],
                'entrega' => $entrega ? [
                    'id' => (int) $entrega['id'],
                    'archivo_nombre_original' => $entrega['archivo_nombre_original'],
                    'archivo_ruta' => $entrega['archivo_ruta'],
                    'comentario_alumno' => $entrega['comentario_alumno'],
                    'fecha_entrega' => $entrega['fecha_entrega'],
                ] : null,
            ];
        }, $evaluaciones);

        echo json_encode([
            'data' => $evaluacionesMapeadas,
            'materia' => [
                'id' => (int) $asignacion['id_unidad_curricular'],
                'nombre' => $asignacion['uc_nombre'],
                'codigo' => $asignacion['codigo'],
                'trayecto' => $asignacion['trayecto'],
                'seccion' => $asignacion['codigo_seccion'],
            ]
        ], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode([
            'status' => 'error',
            'error' => $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function profesor_materia_recursos_listar(): void
{
    header('Content-Type: application/json; charset=utf-8');

    try {
        $idAsignacion = extraerIdUrl();
        if ($idAsignacion <= 0) {
            throw new Exception('ID de asignación inválido.', 400);
        }

        $payload = AuthMiddleware::getUsuarioPayload();
        $idUsuario = (int) ($payload['sub'] ?? 0);

        $repository = new MateriaRepository();
        $asignacion = $repository->obtenerAsignacionPorId($idAsignacion);

        if (!$asignacion) {
            throw new Exception('Asignación no encontrada.', 404);
        }

        if (!$repository->esDocenteAsignado($idAsignacion, $idUsuario)) {
            throw new Exception('No tienes permisos para ver los recursos de esta materia.', 403);
        }

        $recursos = $repository->obtenerRecursos($idAsignacion);

        echo json_encode(['data' => $recursos], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode([
            'status' => 'error',
            'error' => $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function profesor_materia_recurso_crear(): void
{
    header('Content-Type: application/json; charset=utf-8');

    try {
        $idAsignacion = extraerIdUrl();
        if ($idAsignacion <= 0) {
            throw new Exception('ID de asignación inválido.', 400);
        }

        $payload = AuthMiddleware::getUsuarioPayload();
        $idUsuario = (int) ($payload['sub'] ?? 0);

        $repository = new MateriaRepository();
        $asignacion = $repository->obtenerAsignacionPorId($idAsignacion);

        if (!$asignacion) {
            throw new Exception('Asignación no encontrada.', 404);
        }

        if (!$repository->esDocenteAsignado($idAsignacion, $idUsuario)) {
            throw new Exception('No tienes permisos para gestionar recursos en esta materia.', 403);
        }

        $input = $_POST;
        $archivo = $_FILES['archivo'] ?? null;

        $data = [
            'titulo' => $input['titulo'] ?? '',
            'tipo' => $input['tipo'] ?? 'otro',
            'enlace_url' => $input['enlace_url'] ?? '',
            'archivo' => $archivo,
        ];

        $service = new MateriaService();
        $errores = $service->validarRecurso($data);

        if (!empty($errores)) {
            http_response_code(422);
            echo json_encode([
                'status' => 'error',
                'error' => implode(' ', $errores)
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $archivoRuta = null;
        if ($archivo && $archivo['error'] === UPLOAD_ERR_OK) {
            $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
            $nombreUnico = time() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
            $directorio = BASE_PATH . '/public/uploads/materias/' . $idAsignacion . '/';

            if (!is_dir($directorio)) {
                mkdir($directorio, 0755, true);
            }

            $rutaDestino = $directorio . $nombreUnico;
            if (!move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
                throw new Exception('Error al subir el archivo.', 500);
            }

            $archivoRuta = '/uploads/materias/' . $idAsignacion . '/' . $nombreUnico;
        }

        $recursoDTO = $service->prepararDTO(
            $idAsignacion,
            $input['titulo'],
            $input['descripcion'] ?? null,
            $input['tipo'],
            $archivoRuta,
            $input['enlace_url'] ?: null,
            $idUsuario,
        );

        $idInsertado = $repository->insertarRecurso($recursoDTO);

        registrar_en_bitacora(
            'CREAR',
            "Recurso creado: {$input['titulo']} (Asignación ID: {$idAsignacion})",
            $idUsuario
        );

        $notifRepo = new NotificacionRepository();
        $estudiantes = $repository->obtenerEstudiantesEnAsignacion($idAsignacion);
        foreach ($estudiantes as $est) {
            $notifRepo->insertar(
                (int) $est['id_usuario'],
                'recurso',
                "Nuevo recurso: {$input['titulo']}",
                "Se ha agregado un nuevo recurso en {$asignacion['uc_nombre']}.",
                (int) $asignacion['id_unidad_curricular'] ?? null,
                'recurso'
            );
        }

        http_response_code(201);
        echo json_encode([
            'status' => 'success',
            'data' => ['id' => $idInsertado, 'message' => 'Recurso creado correctamente.']
        ], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode([
            'status' => 'error',
            'error' => $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function profesor_materia_recurso_eliminar(): void
{
    header('Content-Type: application/json; charset=utf-8');

    try {
        $idRecurso = extraerIdUrl();
        if ($idRecurso <= 0) {
            throw new Exception('ID de recurso inválido.', 400);
        }

        $payload = AuthMiddleware::getUsuarioPayload();
        $idUsuario = (int) ($payload['sub'] ?? 0);

        $repository = new MateriaRepository();
        $recurso = $repository->obtenerRecursoPorId($idRecurso);

        if (!$recurso) {
            throw new Exception('Recurso no encontrado.', 404);
        }

        if ((int) $recurso['creado_por'] !== $idUsuario) {
            throw new Exception('Solo el creador del recurso puede eliminarlo.', 403);
        }

        if ($recurso['archivo_ruta']) {
            $rutaArchivo = BASE_PATH . '/public' . $recurso['archivo_ruta'];
            if (file_exists($rutaArchivo)) {
                unlink($rutaArchivo);
            }
        }

        $repository->eliminarRecurso($idRecurso, $idUsuario);

        registrar_en_bitacora(
            'ELIMINAR',
            "Recurso eliminado: {$recurso['titulo']} (ID: {$idRecurso})",
            $idUsuario
        );

        echo json_encode([
            'status' => 'success',
            'message' => 'Recurso eliminado correctamente.'
        ], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode([
            'status' => 'error',
            'error' => $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function profesor_evaluaciones_listar(): void
{
    header('Content-Type: application/json; charset=utf-8');

    try {
        $idAsignacion = extraerIdUrl();
        if ($idAsignacion <= 0) throw new Exception('ID inválido.', 400);

        $payload = AuthMiddleware::getUsuarioPayload();
        $idUsuario = (int) ($payload['sub'] ?? 0);

        $repository = new MateriaRepository();
        $asignacion = $repository->obtenerAsignacionPorId($idAsignacion);
        if (!$asignacion) throw new Exception('Asignación no encontrada.', 404);
        if (!$repository->esDocenteAsignado($idAsignacion, $idUsuario)) {
            throw new Exception('No tienes permisos.', 403);
        }

        $evaluaciones = $repository->obtenerEvaluaciones($idAsignacion);
        echo json_encode(['data' => $evaluaciones], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode(['status' => 'error', 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function profesor_evaluacion_crear(): void
{
    header('Content-Type: application/json; charset=utf-8');

    try {
        $idAsignacion = extraerIdUrl();
        if ($idAsignacion <= 0) throw new Exception('ID inválido.', 400);

        $payload = AuthMiddleware::getUsuarioPayload();
        $idUsuario = (int) ($payload['sub'] ?? 0);

        $repository = new MateriaRepository();
        $asignacion = $repository->obtenerAsignacionPorId($idAsignacion);
        if (!$asignacion) throw new Exception('Asignación no encontrada.', 404);
        if (!$repository->esDocenteAsignado($idAsignacion, $idUsuario)) {
            throw new Exception('No tienes permisos.', 403);
        }

        $input = json_decode(file_get_contents('php://input'), true);
        if (!is_array($input)) throw new Exception('Datos inválidos.', 400);

        $service = new MateriaService();
        $data = [
            'titulo' => $input['titulo'] ?? '',
            'tipo' => $input['tipo'] ?? 'tarea',
            'fecha_entrega' => $input['fecha_entrega'] ?? '',
            'porcentaje' => $input['porcentaje'] ?? null,
        ];
        $errores = $service->validarEvaluacion($data);
        if (!empty($errores)) {
            http_response_code(422);
            echo json_encode(['status' => 'error', 'error' => implode(' ', $errores)], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $porcentaje = !empty($input['porcentaje']) ? (float) $input['porcentaje'] : null;

        $dto = $service->prepararEvaluacionDTO(
            $idAsignacion,
            $input['titulo'],
            $input['descripcion'] ?? null,
            $input['tipo'],
            $porcentaje,
            $input['fecha_entrega'],
            $idUsuario,
        );

        $idInsertado = $repository->insertarEvaluacion($dto);

        registrar_en_bitacora(
            'CREAR',
            "Evaluación creada: {$input['titulo']} (Asignación ID: {$idAsignacion})",
            $idUsuario,
        );

        $notifRepo = new NotificacionRepository();
        $estudiantes = $repository->obtenerEstudiantesEnAsignacion($idAsignacion);
        foreach ($estudiantes as $est) {
            $notifRepo->insertar(
                (int) $est['id_usuario'],
                'evaluacion',
                "Nueva evaluación: {$input['titulo']}",
                "Se ha publicado una nueva evaluación en {$asignacion['uc_nombre']}.",
                (int) $asignacion['id_unidad_curricular'] ?? null,
                'evaluacion'
            );
        }

        http_response_code(201);
        echo json_encode([
            'status' => 'success',
            'data' => ['id' => $idInsertado, 'message' => 'Evaluación creada correctamente.']
        ], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode(['status' => 'error', 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function profesor_evaluacion_eliminar(): void
{
    header('Content-Type: application/json; charset=utf-8');

    try {
        $idEvaluacion = extraerIdUrl();
        if ($idEvaluacion <= 0) throw new Exception('ID inválido.', 400);

        $payload = AuthMiddleware::getUsuarioPayload();
        $idUsuario = (int) ($payload['sub'] ?? 0);

        $repository = new MateriaRepository();
        $evaluacion = $repository->obtenerEvaluacionPorId($idEvaluacion);
        if (!$evaluacion) throw new Exception('Evaluación no encontrada.', 404);

        if ((int) $evaluacion['creado_por'] !== $idUsuario) {
            throw new Exception('Solo el creador puede eliminarla.', 403);
        }

        $repository->eliminarEvaluacion($idEvaluacion, $idUsuario);

        registrar_en_bitacora('ELIMINAR', "Evaluación eliminada: {$evaluacion['titulo']} (ID: {$idEvaluacion})", $idUsuario);

        echo json_encode(['status' => 'success', 'message' => 'Evaluación eliminada correctamente.'], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode(['status' => 'error', 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function profesor_calificaciones_listar(): void
{
    header('Content-Type: application/json; charset=utf-8');

    try {
        $idEvaluacion = extraerIdUrl();
        if ($idEvaluacion <= 0) throw new Exception('ID inválido.', 400);

        $payload = AuthMiddleware::getUsuarioPayload();
        $idUsuario = (int) ($payload['sub'] ?? 0);

        $repository = new MateriaRepository();
        $evaluacion = $repository->obtenerEvaluacionPorId($idEvaluacion);
        if (!$evaluacion) throw new Exception('Evaluación no encontrada.', 404);

        $idAsignacion = (int) $evaluacion['id_asignacion'];
        if (!$repository->esDocenteAsignado($idAsignacion, $idUsuario)) {
            throw new Exception('No tienes permisos.', 403);
        }

        $estudiantes = $repository->obtenerEstudiantesConCalificaciones($idEvaluacion, $idAsignacion);
        echo json_encode(['data' => $estudiantes], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode(['status' => 'error', 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function profesor_calificaciones_guardar(): void
{
    header('Content-Type: application/json; charset=utf-8');

    try {
        $idEvaluacion = extraerIdUrl();
        if ($idEvaluacion <= 0) throw new Exception('ID inválido.', 400);

        $payload = AuthMiddleware::getUsuarioPayload();
        $idUsuario = (int) ($payload['sub'] ?? 0);

        $repository = new MateriaRepository();
        $evaluacion = $repository->obtenerEvaluacionPorId($idEvaluacion);
        if (!$evaluacion) throw new Exception('Evaluación no encontrada.', 404);

        $idAsignacion = (int) $evaluacion['id_asignacion'];
        if (!$repository->esDocenteAsignado($idAsignacion, $idUsuario)) {
            throw new Exception('No tienes permisos.', 403);
        }

        $input = json_decode(file_get_contents('php://input'), true);
        if (!is_array($input) || !isset($input['calificaciones']) || !is_array($input['calificaciones'])) {
            throw new Exception('Datos inválidos.', 400);
        }

        $dtoList = [];
        foreach ($input['calificaciones'] as $item) {
            $idEstudiante = (int) ($item['id_estudiante'] ?? 0);
            if ($idEstudiante <= 0) continue;

            $nota = isset($item['nota']) && $item['nota'] !== '' && $item['nota'] !== null
                ? (float) $item['nota'] : null;
            if ($nota !== null && ($nota < 0 || $nota > 20)) {
                throw new Exception('Las notas deben estar entre 0 y 20.', 422);
            }

            $dtoList[] = new CalificacionDTO(
                $idEvaluacion,
                $idEstudiante,
                $nota,
                $item['observaciones'] ?? null,
            );
        }

        $repository->guardarCalificaciones($dtoList);

        registrar_en_bitacora('CALIFICAR', "Calificaciones guardadas para evaluación ID: {$idEvaluacion}", $idUsuario);

        $notifRepo = new NotificacionRepository();
        $asignacion = $repository->obtenerAsignacionPorId($idAsignacion);
        foreach ($input['calificaciones'] as $item) {
            $idEstudiante = (int) ($item['id_estudiante'] ?? 0);
            if ($idEstudiante <= 0) continue;
            $idUsuarioEst = $repository->obtenerIdUsuarioPorEstudiante($idEstudiante);
            if (!$idUsuarioEst) continue;
            $notifRepo->insertar(
                $idUsuarioEst,
                'calificacion',
                "Calificación publicada: {$evaluacion['titulo']}",
                "Tu calificación de {$evaluacion['titulo']} en {$asignacion['uc_nombre']} ha sido publicada.",
                (int) $asignacion['id_unidad_curricular'] ?? null,
                'calificacion'
            );
        }

        echo json_encode(['status' => 'success', 'message' => 'Calificaciones guardadas correctamente.'], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode(['status' => 'error', 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function student_materia_notas(): void
{
    header('Content-Type: application/json; charset=utf-8');

    try {
        $materiaId = extraerIdUrl();
        if ($materiaId <= 0) throw new Exception('ID de materia inválido.', 400);

        $payload = AuthMiddleware::getUsuarioPayload();
        $idUsuario = (int) ($payload['sub'] ?? 0);

        $repository = new MateriaRepository();
        $asignacion = $repository->obtenerAsignacionEstudiante($idUsuario, $materiaId);
        if (!$asignacion) throw new Exception('Materia no encontrada.', 404);

        $notas = $repository->obtenerNotasEstudiante($idUsuario, (int) $asignacion['id']);

        $colores = [
            'tarea' => '#0d6efd',
            'examen' => '#dc3545',
            'proyecto' => '#198754',
            'taller' => '#fd7e14',
            'otro' => '#6f42c1',
        ];

        $notasMapeadas = array_map(function ($n) use ($colores) {
            return [
                'id' => (int) $n['evaluacion_id'],
                'titulo' => $n['titulo'],
                'tipo' => $n['tipo'],
                'porcentaje' => $n['porcentaje'] ? (float) $n['porcentaje'] : null,
                'fecha_estimada' => $n['fecha_estimada'],
                'nota' => $n['nota'] !== null ? (float) $n['nota'] : null,
                'observaciones' => $n['observaciones'],
                'color' => $colores[$n['tipo']] ?? $colores['otro'],
            ];
        }, $notas);

        echo json_encode([
            'data' => $notasMapeadas,
            'materia' => [
                'id' => (int) $asignacion['id_unidad_curricular'],
                'nombre' => $asignacion['uc_nombre'],
                'codigo' => $asignacion['codigo'],
            ]
        ], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode(['status' => 'error', 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function student_calendar_events(): void
{
    header('Content-Type: application/json; charset=utf-8');

    try {
        $payload = AuthMiddleware::getUsuarioPayload();
        $idUsuario = (int) ($payload['sub'] ?? 0);
        if ($idUsuario <= 0) throw new Exception('Usuario no autenticado', 401);

        $repository = new MateriaRepository();
        $evaluaciones = $repository->obtenerEvaluacionesEstudiante($idUsuario);

        $colores = [
            'tarea' => '#0d6efd',
            'examen' => '#dc3545',
            'proyecto' => '#198754',
            'taller' => '#fd7e14',
            'otro' => '#6f42c1',
        ];

        $eventos = array_map(function ($e) use ($colores) {
            $fecha = $e['fecha_estimada'];
            return [
                'id' => (int) $e['id'],
                'title' => $e['titulo'] . ' - ' . ($e['materia_nombre'] ?? ''),
                'date' => date('Y-m-d', strtotime($fecha)),
                'color' => $colores[$e['tipo']] ?? $colores['otro'],
                'description' => $e['descripcion'] ?? '',
                'type' => $e['tipo'],
                'materia' => $e['materia_nombre'] ?? '',
                'materia_id' => isset($e['materia_id']) ? (int) $e['materia_id'] : null,
                'codigo' => $e['materia_codigo'] ?? '',
            ];
        }, $evaluaciones);

        echo json_encode(['data' => $eventos], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode(['status' => 'error', 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function student_evaluacion_entregar(): void
{
    header('Content-Type: application/json; charset=utf-8');

    try {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception('Método no permitido.', 405);

        $payload = AuthMiddleware::getUsuarioPayload();
        $idUsuario = (int) ($payload['sub'] ?? 0);
        if ($idUsuario <= 0) throw new Exception('Usuario no autenticado', 401);

        $idEvaluacion = extraerIdUrl();
        if ($idEvaluacion <= 0) throw new Exception('ID inválido.', 400);

        $repository = new MateriaRepository();

        if (!$repository->verificarEstudiantePuedeEntregar($idEvaluacion, $idUsuario)) {
            throw new Exception('No tienes permiso para entregar esta evaluación.', 403);
        }

        if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Debes seleccionar un archivo.', 400);
        }

        $archivo = $_FILES['archivo'];
        $maxSize = 10 * 1024 * 1024;
        if ($archivo['size'] > $maxSize) throw new Exception('El archivo supera los 10MB.', 400);

        $ext = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
        $allowed = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'zip', 'rar', 'jpg', 'jpeg', 'png'];
        if (!in_array($ext, $allowed)) throw new Exception('Tipo de archivo no permitido.', 400);

        $idEstudiante = $repository->obtenerIdEstudiantePorUsuario($idUsuario);
        if (!$idEstudiante) throw new Exception('Estudiante no encontrado.', 404);

        $uploadDir = BASE_PATH . '/public/uploads/entregas/' . $idEvaluacion . '/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0775, true);

        $filename = $idEstudiante . '_' . time() . '_' . preg_replace('/[^a-zA-Z0-9._\-]/', '_', $archivo['name']);
        $destino = $uploadDir . $filename;

        if (!move_uploaded_file($archivo['tmp_name'], $destino)) {
            throw new Exception('Error al guardar el archivo.', 500);
        }

        $rutaRelativa = 'uploads/entregas/' . $idEvaluacion . '/' . $filename;
        $comentario = $_POST['comentario_alumno'] ?? '';

        $entregaDTO = new EntregaDTO(
            $idEvaluacion,
            $idEstudiante,
            $rutaRelativa,
            $archivo['name'],
            $comentario ?: null
        );

        $repository->insertarEntrega($entregaDTO);

        echo json_encode(['status' => 'ok', 'message' => 'Entrega realizada con éxito.'], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode(['status' => 'error', 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function profesor_evaluacion_entregas(): void
{
    header('Content-Type: application/json; charset=utf-8');

    try {
        $idEvaluacion = extraerIdUrl();
        if ($idEvaluacion <= 0) throw new Exception('ID inválido.', 400);

        $payload = AuthMiddleware::getUsuarioPayload();
        $idUsuario = (int) ($payload['sub'] ?? 0);
        if ($idUsuario <= 0) throw new Exception('Usuario no autenticado', 401);

        $repository = new MateriaRepository();
        $evaluacion = $repository->obtenerEvaluacionPorId($idEvaluacion);
        if (!$evaluacion) throw new Exception('Evaluación no encontrada.', 404);

        if (!$repository->verificarProfesorTieneAsignacion($idUsuario, $evaluacion['id_asignacion'])) {
            throw new Exception('No tienes permiso para ver estas entregas.', 403);
        }

        $entregas = $repository->obtenerEntregasEvaluacion($idEvaluacion);

        echo json_encode(['data' => $entregas], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode(['status' => 'error', 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}
