<?php
$pageStyles = [
    '/css/modules/dashboard/inicio.css',
    '/plugins/datatables/css/dataTables.bootstrap5.min.css',
    '/plugins/datatables/css/responsive.bootstrap5.min.css',
];
$pageModuleScripts = ['/js/modules/trayectos/consultar.js'];
$pageScripts = [
    '/plugins/jquery/jquery.min.js',
    '/plugins/datatables/js/dataTables.min.js',
    '/plugins/datatables/js/dataTables.bootstrap5.min.js',
    '/plugins/datatables/js/dataTables.responsive.min.js',
    '/plugins/datatables/js/responsive.bootstrap5.min.js',
];
$contentView = 'trayectos/consultar_content.php';
require_once BASE_PATH . '/src/views/layouts/admin.php';
