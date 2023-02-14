<?php

namespace dwesgram\controlador;

use dwesgram\controlador\Controlador;
use dwesgram\modelo\Entrada;
use dwesgram\modelo\EntradaBd;


class EntradaControlador extends Controlador
{

    public function lista(): array
    {
        $this->vista = 'entrada/lista';
        return EntradaBd::getEntradas();
    }

    public function detalle(): Entrada|null
    {
        $this->vista = "entrada/detalle";
        $id = $_GET && isset($_GET['id']) ? $_GET['id'] : null;
        return EntradaBd::getEntrada($id);
    }

    public function nuevo(): Entrada|null
    {
        //si no hay usuario logueado
        if (!$this->autenticado()) {
            return null;
        }
        //si no hay post
        if (!$_POST) {
            $this->vista = 'entrada/nuevo';
            return null;
        }
        //si hay

        //se recogen datos del post
        $texto = $_POST && isset($_POST['texto']) ? htmlspecialchars($_POST['texto']) : null;
        $creado = time();
        $entrada = new Entrada(texto: $texto, creado: $creado);

        //comprueba las posibles opciones que pueda tener imagen
        if ($entrada->validarImagenDesdePost($_FILES) === false) {
            $this->vista = 'entrada/nuevo';
            return $entrada;
        }

        //si la entrada es valida, se inserta, si no, vuelve al formulario, mostrando los errores
        if ($entrada->esValida()) {

            $this->vista = 'entrada/detalle';
            //se inserta
            $id = EntradaBd::insertar($entrada);
            //y se devuelve getEntrada de la entrada insertada
            return EntradaBd::getEntrada($id);
        } else {

            $this->vista = 'entrada/nuevo';
            return $entrada;
        }
    }

    public function eliminar(): bool
    {

        //si no hay usuario logueado
        if (!$this->autenticado()) {
            return false;
        }
        $this->vista = 'entrada/eliminar';
        $id = $_GET && isset($_GET['id']) ? $_GET['id'] : null;
        $entrada = EntradaBd::getEntrada($id);
        $imagen = $entrada->getImagen();
        //si el usuario no ha escrito la entrada e intenta borrarla
        if ($entrada->getNombreAutor() != $_SESSION['usuario']['nombre']) {
            return false;
        }

        //si el id no es nulo, la elimina
        if ($id != null) {
            //se borra la imagen si existe y no es la por defecto
            if (file_exists($imagen) && $imagen != "imagenes/pordefecto.png") {
                unlink($imagen);
            }
            return EntradaBd::eliminar($id);
        } else {
            return false;
        }
    }
}
