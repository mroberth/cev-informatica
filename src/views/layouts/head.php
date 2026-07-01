<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CEV | Control de Estudios Virtual</title>
    <link rel="shortcut icon" href="/img/cev.png" type="image/x-icon">
    <link rel="stylesheet" href="/plugins/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="/plugins/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/cev-swal.css">
    <?php if (!empty($pageStyles) && is_array($pageStyles)): ?>
    <?php foreach ($pageStyles as $style): ?>
        <link rel="stylesheet" href="<?= $style ?>">
    <?php endforeach; ?>
    <?php endif; ?>
    <script src="/plugins/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="/plugins/sweetalert2/sweetalert.js"></script>
    <script src="/plugins/datatables/"></script>
</head>