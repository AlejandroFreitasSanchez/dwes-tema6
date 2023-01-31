<?php

$resultado = $datosParaVista['datos'];

if($resultado){
    echo "<div class='alert alert-success' role='alert'>Tarea eliminada correctamente</div>";
}else{
    echo "<div class='alert alert-danger' role='alert'>No has podido eliminar la tarea, no existe o no tienes permisos.</div>";
}

echo <<<END
    <p><a href="index.php?controlador=entrada&accion=lista" class="btn btn-primary" role="button">Volver</a></p>
END;