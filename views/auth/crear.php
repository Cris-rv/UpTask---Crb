<div class="contenedor crear">
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Crea tu cuenta en UpTask</p>

        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

        <form class="formulario" method="POSt" action="/crear">
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input 
                    type="text"
                    id="nombre"
                    name="nombre"
                    placeholder="Tu nombre"
                    value="<?php echo $usuario->nombre ?>"
                >
            </div>

            <div class="campo">
                <label for="email">Email</label>
                <input 
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Tu Email"
                    value="<?php echo $usuario->email ?>"
                >
            </div>

            <div class="campo">
                <label for="password">Password</label>
                <input 
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Tu password"
                >
            </div>

            <div class="campo">
                <label for="password2">Repetir Password</label>
                <input 
                    type="password"
                    id="password2"
                    name="password2"
                    placeholder="Repite tu password"
                >
            </div>

            <input 
                class="boton"
                type="submit" 
                value="Crear Cuenta"
            >
        </form>

        <div class="acciones">
            <a href="/">Ya tienes una cuenta? Inicia Sesion</a>
            <a href="/olvide">¿Olvidaste tu password?</a>
        </div>
    </div> <!-- contenedor-sm -->
</div>