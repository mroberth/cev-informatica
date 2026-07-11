<?php
$pageStyles = $pageStyles ?? [];
$pageScripts = $pageScripts ?? [];
$pageModuleScripts = $pageModuleScripts ?? [];
$contentView = $contentView ?? null;

// ── Verificación de permisos por módulo ──────────────────────────
$mapaModulos = [
    'dashboard'             => null,
    'estudiantes'           => 'estudiantes',
    'secciones'             => 'Secciones',
    'unidades-curriculares' => 'Unidades Curriculares',
    'docentes'              => 'Docentes',
    'periodos'              => 'Periodos',
    'inscripciones'         => 'Inscripciones',
    'asignacion-docente'    => 'Asignacion docente',
    'usuarios'              => 'Usuarios',
    'bitacora'              => 'bitacora',
    'trayectos'             => null,
    'calificaciones'        => 'calificaciones',
    'configuracion'         => null,
    'control-acceso'        => null,
];

$rutaActual = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '';
if (preg_match('#/a/([^/]+)#', $rutaActual, $matches)) {
    $segmento = $matches[1];
    $moduloDb = $mapaModulos[$segmento] ?? null;
    if ($moduloDb !== null) {
        $accion = (strpos($rutaActual, '/crear') !== false) ? 'crear' : 'leer';
        verificar_permiso($moduloDb, $accion);
    }
}
// ─────────────────────────────────────────────────────────────────

require_once BASE_PATH . '/src/views/layouts/head.php';
?>
<body>

<div class="wrapper">

  <?php require_once BASE_PATH . '/src/views/layouts/sidebar.php'; ?>

  <div class="content">

    <?php require_once BASE_PATH . '/src/views/layouts/header.php'; ?>

    <?php if (is_string($contentView) && $contentView !== ''): ?>
      <?php require_once BASE_PATH . '/src/views/' . ltrim($contentView, '/'); ?>
    <?php endif; ?>

    <footer class="footer-dashboard">
      &copy; 2026 CEV Informática — PNF en Informática. Todos los derechos reservados.
    </footer>

  </div>

</div>

<?php foreach ($pageScripts as $script): ?>
<script src="<?= $script ?>"></script>
<?php endforeach; ?>

<?php foreach ($pageModuleScripts as $script): ?>
<script type="module" src="<?= $script ?>"></script>
<?php endforeach; ?>

</body>
</html>
