<div class="contenedor olvide">
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Recupera tu Acceso a UpTask</p>

        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

        <form class="formulario" method="POSt" action="/olvide">
            <div class="campo">
                <label for="email">Email</label>
                <input 
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Tu Email"
                >
            </div>

            <input 
                class="boton"
                type="submit" 
                value="Enviar Instrucciones"
            >
        </form>

        <div class="acciones">
            <a href="/">Ya tienes una cuenta? Inicia Sesion</a>
            <a href="/crear">¿Aun no tienes una cuenta? Obtener una</a>
        </div>
    </div> <!-- contenedor-sm -->
</div>