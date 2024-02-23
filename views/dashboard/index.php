<?php include_once __DIR__.'/header-dashboard.php' ?>

    <?php if(count($proyectos)===0){ ?>
        <p class="no-proyectos">No hay proyectos creado <a href="/crear-proyecto">Comienza ahora</a></p>
        <?php } else{ ?>
            <ul class="listado-proyectos">
                <?php foreach($proyectos as $proyecto): ?>
                  
                    <li class="proyecto">
                        <a href="/proyecto?id=<?php echo  $proyecto->url; ?>">
                            <?php echo $proyecto->proyecto; ?>
                        </a>
                    </li>
                <?php endforeach ?>
            </ul>
    <?php }; ?>
<?php include_once __DIR__.'/footer-dashboard.php' ?>
