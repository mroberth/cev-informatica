<?php
$pageStyles = $pageStyles ?? [];
$pageScripts = $pageScripts ?? [];
$pageModuleScripts = $pageModuleScripts ?? [];
$contentView = $contentView ?? null;

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