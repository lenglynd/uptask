<div class="login contenedor">
<?php include_once __DIR__.'/../templates/nombre-sitio.php'; ?>
<?php include_once __DIR__.'/../templates/alertas.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Iniciar Sesión</p>

        <form action="/" class="formulario" method="POST">
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" name="email" placeholder="Tu email" id="email">
            </div>
            <div class="campo">
                <label for="password">Password</label>
                <input type="password" name="password" placeholder="Tu password" id="password">
            </div>
            <input type="submit" value="Iniciar Sesión" class="boton">
        </form>
        <div class="acciones">
            <a href="/crear">¿Aún no tienes cuenta?</a>
            <a href="/olvide">Recupera tu clave</a>
        </div>
    </div>

</div>