<?php include_once __DIR__.'/header-dashboard.php' ?>
<div class="contenedor-sm">
    <?php include_once __DIR__.'/../templates/alertas.php'; ?>
    <a href="/cambiar-password" class="enlace">Cambiar Clave</a>
    <form action="/perfil" method="post" class="formulario">
        <div class="campo">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" placeholder="Tu nombre" id="nombre" value="<?php echo $usuario->nombre ?>">
        </div>
        <div class="campo">
            <label for="email">Email</label>
            <input type="email" name="email" placeholder="Tu Email" id="email" value="<?php echo $usuario->email ?>">
        </div>
        <input type="submit" value="Guardar Cambios">
    </form>
 </div>


<?php include_once __DIR__.'/footer-dashboard.php' ?>
