<?php

use Core\Database\Conexion;
use Core\Middleware\AuthMiddleware;

function evaluaciones_notas(): void
{
    $pageStyles = [
        '/css/modules/dashboard/inicio.css',
        '/plugins/datatables/css/dataTables.bootstrap5.min.css',
        '/plugins/datatables/css/buttons.bootstrap5.min.css',
        '/plugins/datatables/css/responsive.bootstrap5.min.css',
    ];
    $pageScripts = [
        '/plugins/jquery/jquery.min.js',
        '/plugins/datatables/js/dataTables.min.js',
        '/plugins/datatables/js/dataTables.bootstrap5.min.js',
        '/plugins/datatables/js/jszip.min.js',
        '/plugins/datatables/js/pdfmake.min.js',
        '/plugins/datatables/js/vfs_fonts.js',
        '/plugins/datatables/js/dataTables.buttons.min.js',
        '/plugins/datatables/js/buttons.html5.min.js',
        '/plugins/datatables/js/buttons.print.min.js',
        '/plugins/datatables/js/buttons.bootstrap5.min.js',
        '/plugins/datatables/js/dataTables.responsive.min.js',
        '/plugins/datatables/js/responsive.bootstrap5.min.js',
    ];
    $pageModuleScripts = ['/js/modules/evaluaciones-notas/evaluaciones-notas.js'];
    $contentView = 'evaluaciones_notas/evaluaciones_notas_content.php';
    require_once BASE_PATH . '/src/views/layouts/admin.php';
}

function evaluaciones_notas_data(): void
{
    header('Content-Type: application/json; charset=utf-8');
    try {
        AuthMiddleware::procesar();
        $db = Conexion::obtenerConexion('business');

        $filtroTrayecto = $_GET['trayecto'] ?? '';
        $filtroSeccion = $_GET['seccion'] ?? '';
        $filtroMateria = $_GET['materia'] ?? '';

        $where = [];
        $params = [];

        if ($filtroTrayecto !== '') {
            $where[] = 't.id = :trayecto';
            $params[':trayecto'] = (int) $filtroTrayecto;
        }
        if ($filtroSeccion !== '') {
            $where[] = 's.id = :seccion';
            $params[':seccion'] = (int) $filtroSeccion;
        }
        if ($filtroMateria !== '') {
            $where[] = 'uc.id = :materia';
            $params[':materia'] = (int) $filtroMateria;
        }

        $sqlWhere = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        $query = "SELECT e.id, e.titulo, e.tipo, e.porcentaje, e.fecha_estimada,
                         e.id_asignacion,
                         uc.nombre AS materia_nombre, uc.codigo AS materia_codigo,
                         t.nombre AS trayecto, s.codigo_seccion,
                         CONCAT(u.nombre, ' ', u.apellido) AS docente_nombre,
                         (SELECT COUNT(*) FROM estudiantes est2
                          JOIN inscripciones i2 ON est2.id = i2.id_estudiante AND i2.estado = 'Cursando'
                          WHERE i2.id_seccion = s.id) AS total_estudiantes,
                         (SELECT ROUND(AVG(nota), 1) FROM calificaciones WHERE id_evaluacion = e.id AND nota IS NOT NULL) AS promedio_nota,
                         (SELECT COUNT(*) FROM calificaciones WHERE id_evaluacion = e.id AND nota IS NOT NULL) AS calificadas
                  FROM evaluaciones e
                  JOIN asignaciones_docentes ad ON e.id_asignacion = ad.id
                  JOIN unidades_curriculares uc ON ad.id_unidad_curricular = uc.id
                  JOIN secciones s ON ad.id_seccion = s.id
                  JOIN trayectos t ON s.id_trayecto = t.id
                  JOIN docentes d ON ad.id_docente = d.id
                  JOIN cev_security.usuarios u ON d.id_usuario = u.id
                  {$sqlWhere}
                  ORDER BY t.nombre, s.codigo_seccion, uc.nombre, e.fecha_estimada DESC";

        $stmt = $db->prepare($query);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v, is_int($v) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->execute();
        $evaluaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $filtros = obtener_filtros_evaluaciones($db);

        echo json_encode([
            'data' => $evaluaciones,
            'filtros' => $filtros,
        ], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function evaluacion_estudiantes(): void
{
    header('Content-Type: application/json; charset=utf-8');
    try {
        AuthMiddleware::procesar();
        $idEvaluacion = extraer_id_url_evaluaciones();
        if ($idEvaluacion <= 0) throw new Exception('ID de evaluación inválido.', 400);

        $db = Conexion::obtenerConexion('business');

        $query = "SELECT est.id AS id_estudiante, u.nombre, u.apellido,
                         c.id AS calificacion_id, c.nota, c.observaciones
                  FROM estudiantes est
                  JOIN cev_security.usuarios u ON est.id_usuario = u.id
                  JOIN inscripciones i ON est.id = i.id_estudiante AND i.estado = 'Cursando'
                  JOIN asignaciones_docentes ad ON i.id_seccion = ad.id_seccion
                  JOIN evaluaciones e ON e.id_asignacion = ad.id AND e.id = :idEvaluacion
                  LEFT JOIN calificaciones c ON c.id_evaluacion = e.id AND c.id_estudiante = est.id
                  ORDER BY u.apellido, u.nombre";

        $stmt = $db->prepare($query);
        $stmt->bindValue(':idEvaluacion', $idEvaluacion, PDO::PARAM_INT);
        $stmt->execute();
        $estudiantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['data' => $estudiantes], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
        http_response_code($code);
        echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function extraer_id_url_evaluaciones(): int
{
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $segments = explode('/', trim($uri, '/'));
    foreach (array_reverse($segments) as $seg) {
        if (ctype_digit($seg)) return (int) $seg;
    }
    return 0;
}

function obtener_filtros_evaluaciones(PDO $db): array
{
    $trayectos = $db->query("SELECT id, nombre FROM trayectos ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
    $secciones = $db->query("SELECT id, codigo_seccion FROM secciones ORDER BY codigo_seccion")->fetchAll(PDO::FETCH_ASSOC);
    $materias = $db->query("SELECT id, nombre, codigo FROM unidades_curriculares ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
    return [
        'trayectos' => $trayectos,
        'secciones' => $secciones,
        'materias' => $materias,
    ];
}
