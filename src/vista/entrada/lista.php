<?php

if (!empty($datosParaVista['datos'])) {
    echo "<h3>Esta es tu lisa de tareas por hacer:</h3>";
    echo "<div class='row' style='padding: 20px;'>";

    foreach ($datosParaVista['datos'] as $entrada) {
        $texto = $entrada->getTexto();
        $id = $entrada->getId();
        $img = $entrada->getImagen();
        $nombreAutor = $entrada->getNombreAutor();
        $megustas = $entrada->getMegustas();
?>

        <div class="col-sm-6 col-md-2">
            <div class="thumbnail">
                <p><b>Escrito por <?= $nombreAutor ?></b></p>
                <img src="<?= $img  ?> "width=150 height=150>
                <div class="caption">

                    <p><?= $texto ?></p>
                    <p>
                        <a href="index.php?controlador=entrada&accion=detalle&id=<?= $id ?>" class="btn btn-primary" role="button">Ver</a>
                        <?php
                        //Casos boton like
                        if ($sesion->haySesion() && $nombreAutor == $_SESSION['usuario']['nombre']) {
                        ?>
                            <a href='index.php?controlador=entrada&accion=eliminar&id=<?= $id ?>' class='btn btn-danger' role='button'>Eliminar</a>
                            <i class="bi bi-hand-thumbs-up">(<?= $megustas ?>)</i>
                        <?php
                        }
                        if ($sesion->haySesion() && $nombreAutor != $_SESSION['usuario']['nombre']) {

                        ?>
                            <a href="index.php?controlador=megusta&accion=pulsarMeGustaDesdeLista&id=<?= $id ?>" role="button"><i class="bi bi-hand-thumbs-up">(<?= $megustas ?>)</i></a>
                        <?php
                        }
                        if (!$sesion->haySesion()) {
                        ?>
                            <i class="bi bi-hand-thumbs-up">(<?= $megustas ?>)</i>
                        <?php
                        }
                        ?>
                    </p>
                </div>
            </div>
        </div>


<?php

    }
    echo "</div>";
    echo "</ul>";
} else {
    echo "<h3>Tu lista de cosas por hacer está vacía</h3>";
}
