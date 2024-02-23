<?php include_once __DIR__.'/header-dashboard.php' ?>
<div class="contenedor-sm">
    <?php include_once __DIR__.'/../templates/alertas.php'; ?>
    <a href="/perfil" class="enlace">Volver a perfil</a>
    <form action="/cambiar-password" method="post" class="formulario">
        <div class="campo">
            <label for="password">Password Actual</label>
            <input type="password" name="password_actual" placeholder="Clave anterior" id="password_actual" value="">
        </div>
        <div class="campo">
            <label for="password">Password Nuevo</label>
            <input type="password" name="password_nuevo" placeholder="Tu password nuevo" id="password" value="">
        </div>
        <input type="submit" value="Guardar Cambios">
    </form>
 </div>


<?php include_once __DIR__.'/footer-dashboard.php' ?>
