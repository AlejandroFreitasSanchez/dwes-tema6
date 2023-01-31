<?php

if (!empty($datosParaVista['datos']) && $datosParaVista['datos'] != null) {
    $entrada = $datosParaVista['datos'];
    $texto = $entrada->getTexto();
    $id = $entrada->getId();
    $img = $entrada->getImagen();
    $dt = new \DateTime('@' . $entrada->getCreado());
    $dtstr = $dt->format('r');
    $creado = $entrada->getCreado();
    $nombreUsuario = $entrada->getNombreAutor();
?>

    <div class='row' style='padding: 20px;'>
        <div class="col-sm-6 col-md-2">
            <div class="thumbnail">
                <h2>Escrito por: <?= $nombreUsuario ?></h2>
                <?php
                if ($img != null) {
                    echo "<img src=$img width=500 height=500>";
                }
                ?>
                <div class="caption">
                    <div class='alert alert-info' role='alert'><?= $texto ?></div>
                   
                    <?php
                    if ($sesion->haySesion() && $nombreUsuario == $_SESSION['usuario']['nombre']) {
                    ?>
                        <p><a href='index.php?controlador=entrada&accion=eliminar&id=<?= $id ?>' class='btn btn-danger' role='button'>Eliminar</a></p>
                    <?php
                    }
                    ?>
                    <p><?= $dtstr ?></p>
                </div>
            </div>
        </div>

    <?php

} else {
    echo "<p>No existe este item</p>";
}

    ?>