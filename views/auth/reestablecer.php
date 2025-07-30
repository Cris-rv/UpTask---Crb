<div class="contenedor reestablecer">
    
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Reestablecer Password</p>

        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

        <?php if($mostrar) : ?>
        <form class="formulario" method="POST">
            <div class="campo">
                <label for="password">Password</label>
                <input 
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Tu password"
                >
            </div>

            <input 
                class="boton"
                type="submit" 
                value="Guardar Password"
            >
        </form>

        <div class="acciones">
            <a href="/crear">¿Aun no tienes una cuenta? Obtener una</a>
            <a href="/olvide">¿Olvidaste tu password?</a>
        </div>
    </div> <!-- contenedor-sm -->
    <?php endif; ?>
</div>