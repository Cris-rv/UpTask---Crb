<?php include_once __DIR__ . '/../dashboard/header-dashboard.php'; ?>

<div class="contenedor-sm">
    <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

    <a href="/perfil" class="enlace">Volver al Perfil</a>

    <form method="POST" class="formulario">
        <div class="campo">
            <label for="password_actual">Password Actual</label>
            <input 
                type="password"
                name="password_actual"
                placeholder="Tu Password Actual"
            />
        </div>

        <div class="campo">
            <label for="password_nuevo">Password Nuevo</label>
            <input 
                type="password"
                name="password_nuevo"
                placeholder="Tu Password Nuevo"
            />
        </div>

        <input type="submit" class="boton" value="Guardar Cambios">
    </form>
</div>

<?php include_once __DIR__ . '/../dashboard/footer-dashboard.php'; ?>