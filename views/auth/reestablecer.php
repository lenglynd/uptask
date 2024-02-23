<div class="reestablecer contenedor">
<?php include_once __DIR__.'/../templates/nombre-sitio.php'; ?>
<?php include_once __DIR__.'/../templates/alertas.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Reestablecer clave</p>
<?php if ($mostrar): ?>
        <form class="formulario" method="POST">
            
            <div class="campo">
                <label for="password">Password</label>
                <input type="password" name="password" placeholder="Tu password" id="password">
            </div>
            <div class="campo">
                <label for="password2">Repetir Password</label>
                <input type="password" name="password2" placeholder="Repite tu password" id="password2">
            </div>
            <input type="submit" value="Guardar cambio" class="boton">
        </form>
    <?php endif; ?>
        <div class="acciones">
            <a href="/crear">¿Aún no tienes cuenta?</a>
            <a href="/">Inicia Sesión</a>
        </div>
    </div>

</div>