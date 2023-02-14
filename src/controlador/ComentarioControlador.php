<?

namespace dwesgram\controlador;

use dwesgram\controlador\Controlador;
use dwesgram\modelo\Entrada;
use dwesgram\modelo\EntradaBd;
use dwesgram\modelo\Comentario;
use dwesgram\modelo\ComentarioBd;

class ComentarioControlador extends Controlador
{

    public function nuevo(): Entrada|null
    {
        //si no hay usuario logueado
        if (!$this->autenticado()) {
            return null;
        }

        //se recogen los datos
        $idEntrada = $_GET && isset($_GET['id']) ? $_GET['id'] : null;
        $idUsuario = $_SESSION['usuario']['id'];
        $texto = $_POST && isset($_POST['comentario']) ? $_POST['comentario'] : "";


        //se crea un nuevo comentario 
        $comentario = new Comentario(idUsuario: $idUsuario, idEntrada: $idEntrada, texto: $texto);

        //se inserta en la base de datos si el texto no esta vacio
        if (strlen($texto) > 0) {
            $id = ComentarioBd::insertar($comentario);
        }


        //devuelve a la vista  de lista de entradas, por lo que se llama al metodo getEntradas
        $this->vista = "entrada/detalle";
        return EntradaBd::getEntrada($idEntrada);
    }
}
