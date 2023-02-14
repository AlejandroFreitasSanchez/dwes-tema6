<?php

if (!empty($datosParaVista['datos']) && $datosParaVista['datos'] != null) {
    $entrada = $datosParaVista['datos'];
    $texto = $entrada->getTexto();
    $id = $entrada->getId();
    $img = $entrada->getImagen();
    $dt = new \DateTime('@' . $entrada->getCreado());
    $dtstr = $dt->format('r');
    $creado = $entrada->getCreado();
    $nombreAutor = $entrada->getNombreAutor();
    $megustas = $entrada->getMegustas();
?>

    <div class='row' style='padding: 20px;'>
        <div class="col-sm-6 col-md-2">
            <div class="thumbnail">
                <h2>Escrito por: <?= $nombreAutor ?></h2>
                <?php
                if ($img != null) {
                    echo "<img src=$img width=300 height=300>";
                }
                ?>
                <div class="caption">
                    <div class='alert alert-info' role='alert'><?= $texto ?></div>
                   
                    <?php
                        if ($sesion->haySesion() && $nombreAutor == $_SESSION['usuario']['nombre']) {
                        ?>
                            <a href='index.php?controlador=entrada&accion=eliminar&id=<?= $id ?>' class='btn btn-danger' role='button'>Eliminar</a>
                            <i class="bi bi-hand-thumbs-up">(<?= $megustas ?>)</i>
                        <?php
                        //casos boton like
                        }
                        if ($sesion->haySesion() && $nombreAutor != $_SESSION['usuario']['nombre']) {

                        ?>
                            <a href="index.php?controlador=megusta&accion=pulsarMeGustaDesdeDetalle&id=<?= $id ?>" role="button"><i class="bi bi-hand-thumbs-up">(<?= $megustas ?>)</i></a>
                        <?php
                        }
                        if (!$sesion->haySesion()) {
                        ?>
                            <i class="bi bi-hand-thumbs-up">(<?= $megustas ?>)</i>
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