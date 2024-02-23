<div class="crear contenedor">
    <?php include_once __DIR__.'/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Crea tu cuenta en UpTask</p>
        <?php include_once __DIR__.'/../templates/alertas.php'; ?>
        <form action="/crear" class="formulario" method="POST">
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" placeholder="Tu nombre" id="nombre" value="<?php echo $usuario->nombre; ?>">
            </div>
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" name="email" placeholder="Tu email" id="email" value="<?php echo $usuario->email; ?>">
            </div>
            <div class="campo">
                <label for="password">Password</label>
                <input type="password" name="password" placeholder="Tu password" id="password">
            </div>
            <div class="campo">
                <label for="password2">Repetir Password</label>
                <input type="password" name="password2" placeholder="Repite tu password" id="password2">
            </div>
            <input type="submit" value="Crear cuenta" class="boton">
        </form>
        <div class="acciones">
            <a href="/">Inicia Sessión</a>
            <a href="/olvide">Recupera tu clave</a>
        </div>
    </div>

</div>