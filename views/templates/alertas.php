<?php foreach($alertas as $key => $alerta) : // $key => $alerta es para acceder al arreglo de error y $alerta => $mensaje es para acceder a cada mensaje del arreglo
    foreach($alerta  as $mensaje) : ?>  
        <div class="alerta <?php echo $key; ?>">
            <?php echo $mensaje; ?>
        </div>
<?php endforeach;
    endforeach; ?>