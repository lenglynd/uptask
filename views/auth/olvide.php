<div class="olvide contenedor">
<?php include_once __DIR__.'/../templates/nombre-sitio.php'; ?>
<?php include_once __DIR__.'/../templates/alertas.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Recuperar clave</p>

        <form action="/olvide" class="formulario" method="POST">
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" name="email" placeholder="Tu email" id="email">
            </div>
            
            <input type="submit" value="Recuperar" class="boton">
        </form>
        <div class="acciones">
            <a href="/">Inicia sesión</a>
            <a href="/crear">¿Aún no tienes cuenta?</a>
        </div>
    </div>

</div>