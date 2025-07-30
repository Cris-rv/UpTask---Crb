<?php include_once __DIR__ . '/../dashboard/header-dashboard.php'; ?>

<div class="contenedor-sm">
    <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

    <form class="formulario" action="/crear-proyecto" method="POST">

    <?php include_once __DIR__ . '/../dashboard/formulario-proyecto.php'; ?>

    <input type="submit" class="boton" value="Crear Proyecto">
    </form>
</div>

<?php include_once __DIR__ . '/../dashboard/footer-dashboard.php'; ?>