<?php

use Core\Database\Conexion;
use Core\Middleware\AuthMiddleware;

function reportes(): void
{
    $pageStyles = [
        '/css/modules/dashboard/inicio.css',
    ];
    $pageScripts = [
        '/plugins/chart.js/chart.umd.min.js',
    ];
    $pageModuleScripts = ['/js/modules/reportes/reportes.js'];
    $contentView = 'reportes/reportes_content.php';
    require_once BASE_PATH . '/src/views/layouts/admin.php';
}

function reportes_data(): void
{
    header('Content-Type: application/json; charset=utf-8');
    try {
        AuthMiddleware::procesar();
        $db = Conexion::obtenerConexion('business');

        $filtroPeriodo = $_GET['periodo'] ?? '';

        $estudiantes = obtener_estadisticas_estudiantes($db, $filtroPeriodo);
        $rendimiento = obtener_estadisticas_rendimiento($db, $filtroPeriodo);
        $docentes = obtener_estadisticas_docentes($db, $filtroPeriodo);
        $inscripciones = obtener_tendencia_inscripciones($db);
        $distribucion = obtener_distribucion_notas($db, $filtroPeriodo);
        $periodos = $db->query("SELECT id, nombre FROM periodos_academicos ORDER BY nombre DESC")->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'data' => [
                'estudiantes' => $estudiantes,
                'rendimiento' => $rendimiento,
                'docentes' => $docentes,
                'inscripciones' => $inscripciones,
                'distribucion_notas' => $distribucion,
            ],
            'filtros' => [
                'periodos' => $periodos,
            ],
        ], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

function obtener_estadisticas_estudiantes(PDO $db, string $filtroPeriodo): array
{
    $wherePeriodo = '';
    $params = [];
    if ($filtroPeriodo !== '') {
        $wherePeriodo = 'AND s.id_periodo = :periodo';
        $params[':periodo'] = (int) $filtroPeriodo;
    }

    $query = "SELECT COUNT(*) AS total FROM estudiantes";
    $total = (int) $db->query($query)->fetchColumn();

    $query = "SELECT e.estado_academico, COUNT(*) AS cantidad
              FROM estudiantes e
              GROUP BY e.estado_academico";
    $porEstado = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

    $query = "SELECT t.nombre AS trayecto, COUNT(DISTINCT est.id) AS cantidad
              FROM estudiantes est
              JOIN inscripciones i ON est.id = i.id_estudiante AND i.estado = 'Cursando'
              JOIN secciones s ON i.id_seccion = s.id
              JOIN trayectos t ON s.id_trayecto = t.id
              {$wherePeriodo}
              GROUP BY t.id, t.nombre
              ORDER BY t.nombre";
    $stmt = $db->prepare($query);
    foreach ($params as $k => $v) {
        $stmt->bindValue($k, $v, PDO::PARAM_INT);
    }
    $stmt->execute();
    $porTrayecto = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return [
        'total' => $total,
        'por_estado' => $porEstado,
        'por_trayecto' => $porTrayecto,
    ];
}

function obtener_estadisticas_rendimiento(PDO $db, string $filtroPeriodo): array
{
    $where = '';
    $params = [];
    if ($filtroPeriodo !== '') {
        $where = 'AND s.id_periodo = :periodo';
        $params[':periodo'] = (int) $filtroPeriodo;
    }

    $query = "SELECT
                ROUND(AVG(c.nota), 1) AS promedio_general,
                SUM(CASE WHEN c.nota >= 10 THEN 1 ELSE 0 END) AS aprobados,
                SUM(CASE WHEN c.nota IS NOT NULL AND c.nota < 10 THEN 1 ELSE 0 END) AS reprobados,
                COUNT(c.id) AS total_calificadas
              FROM calificaciones c
              JOIN evaluaciones e ON c.id_evaluacion = e.id
              JOIN asignaciones_docentes ad ON e.id_asignacion = ad.id
              JOIN secciones s ON ad.id_seccion = s.id
              {$where}";
    $stmt = $db->prepare($query);
    foreach ($params as $k => $v) {
        $stmt->bindValue($k, $v, PDO::PARAM_INT);
    }
    $stmt->execute();
    $general = $stmt->fetch(PDO::FETCH_ASSOC);

    $query = "SELECT
                uc.nombre AS materia,
                ROUND(AVG(c.nota), 1) AS promedio,
                ROUND(SUM(CASE WHEN c.nota >= 10 THEN 1 ELSE 0 END) * 100.0 / COUNT(c.id), 1) AS aprobacion
              FROM calificaciones c
              JOIN evaluaciones e ON c.id_evaluacion = e.id
              JOIN asignaciones_docentes ad ON e.id_asignacion = ad.id
              JOIN unidades_curriculares uc ON ad.id_unidad_curricular = uc.id
              JOIN secciones s ON ad.id_seccion = s.id
              {$where}
              GROUP BY uc.id, uc.nombre
              ORDER BY aprobacion DESC";
    $stmt = $db->prepare($query);
    foreach ($params as $k => $v) {
        $stmt->bindValue($k, $v, PDO::PARAM_INT);
    }
    $stmt->execute();
    $porMateria = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return [
        'promedio_general' => $general['promedio_general'] ?? 0,
        'aprobados' => (int) ($general['aprobados'] ?? 0),
        'reprobados' => (int) ($general['reprobados'] ?? 0),
        'total_calificadas' => (int) ($general['total_calificadas'] ?? 0),
        'por_materia' => $porMateria,
    ];
}

function obtener_estadisticas_docentes(PDO $db, string $filtroPeriodo): array
{
    $where = '';
    $params = [];
    if ($filtroPeriodo !== '') {
        $where = 'WHERE s.id_periodo = :periodo';
        $params[':periodo'] = (int) $filtroPeriodo;
    }

    $query = "SELECT COUNT(*) FROM docentes WHERE estado = 'Activo'";
    $total = (int) $db->query($query)->fetchColumn();

    $query = "SELECT COUNT(DISTINCT ad.id_docente) AS con_materias
              FROM asignaciones_docentes ad
              JOIN secciones s ON ad.id_seccion = s.id
              {$where}";
    $stmt = $db->prepare($query);
    foreach ($params as $k => $v) {
        $stmt->bindValue($k, $v, PDO::PARAM_INT);
    }
    $stmt->execute();
    $conMaterias = (int) $stmt->fetchColumn();

    $query = "SELECT ROUND(AVG(cnt), 1) AS carga_promedio
              FROM (
                SELECT ad.id_docente, COUNT(*) AS cnt
                FROM asignaciones_docentes ad
                JOIN secciones s ON ad.id_seccion = s.id
                {$where}
                GROUP BY ad.id_docente
              ) sub";
    $stmt = $db->prepare($query);
    foreach ($params as $k => $v) {
        $stmt->bindValue($k, $v, PDO::PARAM_INT);
    }
    $stmt->execute();
    $cargaPromedio = $stmt->fetchColumn() ?? 0;

    $query = "SELECT
                CONCAT(u.nombre, ' ', u.apellido) AS docente,
                COUNT(ad.id) AS materias
              FROM asignaciones_docentes ad
              JOIN docentes d ON ad.id_docente = d.id
              JOIN cev_security.usuarios u ON d.id_usuario = u.id
              JOIN secciones s ON ad.id_seccion = s.id
              {$where}
              GROUP BY d.id, u.nombre, u.apellido
              ORDER BY materias DESC
              LIMIT 10";
    $stmt = $db->prepare($query);
    foreach ($params as $k => $v) {
        $stmt->bindValue($k, $v, PDO::PARAM_INT);
    }
    $stmt->execute();
    $topDocentes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return [
        'total' => $total,
        'con_materias' => $conMaterias,
        'carga_promedio' => $cargaPromedio,
        'top_docentes' => $topDocentes,
    ];
}

function obtener_tendencia_inscripciones(PDO $db): array
{
    $query = "SELECT
                p.nombre AS periodo,
                COUNT(i.id) AS total
              FROM inscripciones i
              JOIN secciones s ON i.id_seccion = s.id
              JOIN periodos_academicos p ON s.id_periodo = p.id
              GROUP BY p.id, p.nombre
              ORDER BY p.nombre ASC";
    return $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
}

function obtener_distribucion_notas(PDO $db, string $filtroPeriodo): array
{
    $where = '';
    $params = [];
    if ($filtroPeriodo !== '') {
        $where = 'AND s.id_periodo = :periodo';
        $params[':periodo'] = (int) $filtroPeriodo;
    }

    $query = "SELECT
                SUM(CASE WHEN c.nota >= 0 AND c.nota <= 5 THEN 1 ELSE 0 END) AS rango_0_5,
                SUM(CASE WHEN c.nota > 5 AND c.nota <= 10 THEN 1 ELSE 0 END) AS rango_6_10,
                SUM(CASE WHEN c.nota > 10 AND c.nota <= 15 THEN 1 ELSE 0 END) AS rango_11_15,
                SUM(CASE WHEN c.nota > 15 AND c.nota <= 20 THEN 1 ELSE 0 END) AS rango_16_20
              FROM calificaciones c
              JOIN evaluaciones e ON c.id_evaluacion = e.id
              JOIN asignaciones_docentes ad ON e.id_asignacion = ad.id
              JOIN secciones s ON ad.id_seccion = s.id
              {$where}";
    $stmt = $db->prepare($query);
    foreach ($params as $k => $v) {
        $stmt->bindValue($k, $v, PDO::PARAM_INT);
    }
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return [
        'labels' => ['0–5', '6–10', '11–15', '16–20'],
        'data' => [
            (int) ($row['rango_0_5'] ?? 0),
            (int) ($row['rango_6_10'] ?? 0),
            (int) ($row['rango_11_15'] ?? 0),
            (int) ($row['rango_16_20'] ?? 0),
        ],
    ];
}
