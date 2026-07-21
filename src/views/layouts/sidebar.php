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
                'id' => 'subUnidadesCurriculares',
                'titulo' => 'Unidades Curriculares',
                'icono' => 'bi-book',
                'base' => '/a/unidades-curriculares',
                'items' => [
                    ['href' => '/a/unidades-curriculares/crear', 'icono' => 'bi-plus-circle', 'texto' => 'Crear'],
                    ['href' => '/a/unidades-curriculares/consultar', 'icono' => 'bi-search', 'texto' => 'Consultar'],
                ],
            ],
            [
                'id' => 'subSecciones',
                'titulo' => 'Secciones',
                'icono' => 'bi-calendar2-week',
                'base' => '/a/secciones',
                'items' => [
                    ['href' => '/a/secciones/crear', 'icono' => 'bi-plus-circle', 'texto' => 'Crear'],
                    ['href' => '/a/secciones/consultar', 'icono' => 'bi-search', 'texto' => 'Consultar'],
                ],
            ],
            [
                'id' => 'subAsignacionDocente',
                'titulo' => 'Asignación Docente',
                'icono' => 'bi-person-plus',
                'base' => '/a/asignacion-docente',
                'items' => [
                    ['href' => '/a/asignacion-docente/crear', 'icono' => 'bi-plus-circle', 'texto' => 'Crear'],
                    ['href' => '/a/asignacion-docente/consultar', 'icono' => 'bi-search', 'texto' => 'Consultar'],
                ],
            ],
            [
                'id' => 'subInscripciones',
                'titulo' => 'Inscripciones',
                'icono' => 'bi-mortarboard',
                'base' => '/a/inscripciones',
                'items' => [
                    ['href' => '/a/inscripciones/crear', 'icono' => 'bi-plus-circle', 'texto' => 'Crear'],
                    ['href' => '/a/inscripciones/consultar', 'icono' => 'bi-search', 'texto' => 'Consultar'],
                ],
            ],
            [
                'id' => 'subEvaluacionesNotas',
                'titulo' => 'Evaluaciones y Notas',
                'icono' => 'bi-journal-text',
                'base' => '/a/evaluaciones-notas',
                'url' => '/a/evaluaciones-notas',
                'items' => [],
            ],
        ],
    ],
    [
        'grupo' => 'Parámetros Académicos',
        'items' => [
            [
                'id' => 'subPeriodos',
                'titulo' => 'Períodos Académicos',
                'icono' => 'bi-calendar-range',
                'base' => '/a/periodos',
                'items' => [
                    ['href' => '/a/periodos/crear', 'icono' => 'bi-plus-circle', 'texto' => 'Crear'],
                    ['href' => '/a/periodos/consultar', 'icono' => 'bi-search', 'texto' => 'Consultar'],
                ],
            ],
            [
                'id' => 'subTrayectos',
                'titulo' => 'Trayectos y Fases',
                'icono' => 'bi-diagram-3',
                'base' => '/a/trayectos',
                'url' => '/a/trayectos/consultar',
                'items' => [],
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
                'items' => [
                    ['href' => '/a/estudiantes/crear', 'icono' => 'bi-plus-circle', 'texto' => 'Crear'],
                    ['href' => '/a/estudiantes/consultar', 'icono' => 'bi-search', 'texto' => 'Consultar'],
                ],
            ],
            [
                'id' => 'subDocentes',
                'titulo' => 'Docentes',
                'icono' => 'bi-person-badge',
                'base' => '/a/docentes',
                'items' => [
                    ['href' => '/a/docentes/crear', 'icono' => 'bi-plus-circle', 'texto' => 'Crear'],
                    ['href' => '/a/docentes/consultar', 'icono' => 'bi-search', 'texto' => 'Consultar'],
                ],
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
                'url' => '/a/control-acceso',
                'items' => [],
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
                'url' => '/a/reportes',
                'items' => [],
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