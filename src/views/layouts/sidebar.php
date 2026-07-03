<?php
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$rutaActual = '/' . ltrim(substr($requestPath, strlen($scriptDir)), '/');

$esRutaActiva = static function (string $rutaActual, string $rutaObjetivo): bool {
    $rutaObjetivo = rtrim($rutaObjetivo, '/');

    if ($rutaObjetivo === '') {
        return $rutaActual === '/';
    }

    return $rutaActual === $rutaObjetivo || str_starts_with($rutaActual, $rutaObjetivo . '/');
};

$menusSidebar = [
    [
        'grupo' => 'Gestión Académica',
        'items' => [
            [
                'id'=> 'Dashboard',
                'titulo' => 'Dashboard',
                'icono' => 'bi-house',
                'base' => '/a/dashboard',
                'url' => '/a/dashboard',
                'items' => [],
            ],
            [
                'id' => 'subInscripciones',
                'titulo' => 'Inscripciones',
                'icono' => 'bi-journal-plus',
                'base' => '/a/inscripciones',
                'items' => [],
                'placeholder' => true,
            ],
            [
                'id' => 'subPlanificacionAcademica',
                'titulo' => 'Planificación Académica',
                'icono' => 'bi-calendar2-week',
                'base' => '/a/planificacion-academica',
                'items' => [],
                'placeholder' => true,
            ],
            [
                'id' => 'subEvaluacionesNotas',
                'titulo' => 'Evaluaciones y Notas',
                'icono' => 'bi-journal-text',
                'base' => '/a/evaluaciones-notas',
                'items' => [],
                'placeholder' => true,
            ],
        ],
    ],
    [
        'grupo' => 'Personas',
        'items' => [
            [
                'id' => 'subEstudiantes',
                'titulo' => 'Estudiantes',
                'icono' => 'bi-mortarboard',
                'base' => '/a/estudiantes',
                'items' => [],
                'placeholder' => true,
            ],
            [
                'id' => 'subDocentes',
                'titulo' => 'Docentes',
                'icono' => 'bi-person-badge',
                'base' => '/a/docentes',
                'items' => [],
                'placeholder' => true,
            ],
        ],
    ],
    [
        'grupo' => 'Seguridad',
        'items' => [
            [
                'id' => 'subUsuarios',
                'titulo' => 'Usuarios',
                'icono' => 'bi-people',
                'base' => '/a/usuarios',
                'items' => [
                    ['href' => '/a/usuarios/crear', 'icono' => 'bi-plus-circle', 'texto' => 'Crear'],
                    ['href' => '/a/usuarios/consultar', 'icono' => 'bi-search', 'texto' => 'Consultar'],
                ],
            ],
            [
                'id' => 'subControlAcceso',
                'titulo' => 'Control de Acceso',
                'icono' => 'bi-shield-lock',
                'base' => '/a/control-acceso',
                'items' => [],
                'placeholder' => true,
            ],
            [
                'id' => 'subAuditoriaBitacora',
                'titulo' => 'Auditoría y Bitácora',
                'icono' => 'bi-journal-check',
                'base' => '/a/bitacora',
                'items' => [
                    ['href' => '/a/bitacora', 'icono' => 'bi-search', 'texto' => 'Consultar'],
                ],
            ],
            [
                'id' => 'subConfiguracion',
                'titulo' => 'Configuración',
                'icono' => 'bi-gear',
                'base' => '/a/configuracion',
                'items' => [
                    ['href' => '/a/configuracion/crear', 'icono' => 'bi-plus-circle', 'texto' => 'Crear'],
                    ['href' => '/a/configuracion/consultar', 'icono' => 'bi-search', 'texto' => 'Consultar'],
                ],
            ],
        ],
    ],
    [
        'grupo' => 'Operativo',
        'items' => [
            [
                'id' => 'subReportes',
                'titulo' => 'Reportes',
                'icono' => 'bi-graph-up',
                'base' => '/a/reportes',
                'items' => [],
                'placeholder' => true,
            ],
        ],
    ],
];
?>
<!-- ===== SIDEBAR ===== -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <a href="/a/dashboard">
            <img src="<?php echo BASE_URL; ?>img/cev.png" alt="Logo CEV Informática" class="sidebar-logo"></a>
        <span class="sidebar-brand-text">CEV Informática</span>
    </div>
    <nav class="sidebar-nav">
        <?php foreach ($menusSidebar as $grupo): ?>
            <div class="sidebar-group">
                <div class="sidebar-group-title"><?= $grupo['grupo'] ?></div>
                <?php foreach ($grupo['items'] as $menu): ?>
                    <?php
                    $menuActivo = $esRutaActiva($rutaActual, $menu['base']);
                    foreach ($menu['items'] as $item) {
                        if (!empty($item['href']) && $item['href'] !== '#' && $esRutaActiva($rutaActual, $item['href'])) {
                            $menuActivo = true;
                            break;
                        }
                    }
                    ?>
                    <div class="nav-item <?= !empty($menu['placeholder']) ? 'nav-item-placeholder' : '' ?>">
                        <?php if (!empty($menu['url']) && empty($menu['items'])): ?>
                            <a class="nav-link nav-link-static <?= $menuActivo ? 'active' : '' ?>" href="<?= $menu['url'] ?>">
                                <i class="bi <?= $menu['icono'] ?>"></i><?= $menu['titulo'] ?>
                            </a>
                        <?php elseif (!empty($menu['placeholder']) && empty($menu['items'])): ?>
                            <div class="nav-link nav-link-static <?= $menuActivo ? 'active' : '' ?>">
                                <i class="bi <?= $menu['icono'] ?>"></i><?= $menu['titulo'] ?>
                            </div>
                        <?php else: ?>
                            <button class="nav-link <?= $menuActivo ? 'active' : '' ?>" data-bs-toggle="collapse" data-bs-target="#<?= $menu['id'] ?>" aria-expanded="<?= $menuActivo ? 'true' : 'false' ?>">
                                <i class="bi <?= $menu['icono'] ?>"></i><?= $menu['titulo'] ?>
                                <i class="bi bi-chevron-right chevron"></i>
                            </button>
                            <div class="collapse sub-menu <?= $menuActivo ? 'show' : '' ?>" id="<?= $menu['id'] ?>">
                                <?php foreach ($menu['items'] as $item): ?>
                                    <?php $itemActivo = !empty($item['href']) && $item['href'] !== '#' && $esRutaActiva($rutaActual, $item['href']); ?>
                                    <a class="nav-link <?= $itemActivo ? 'active' : '' ?>" href="<?= $item['href'] ?>"><i class="bi <?= $item['icono'] ?>"></i><?= $item['texto'] ?></a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </nav>
</aside>