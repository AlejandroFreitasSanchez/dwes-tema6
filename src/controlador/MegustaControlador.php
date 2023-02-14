<?

namespace dwesgram\controlador;

use dwesgram\controlador\Controlador;
use dwesgram\modelo\Entrada;
use dwesgram\modelo\EntradaBd;
use dwesgram\modelo\Megusta;
use dwesgram\modelo\MegustaBd;

class MegustaControlador extends Controlador
{

    public function pulsarMeGustaDesdeLista(): array|null
    {
        //si no hay usuario logueado
        if (!$this->autenticado()) {
            return null;
        }

        //se recogen los datos
        $idEntrada = $_GET && isset($_GET['id']) ? $_GET['id'] : null;
        $idUsuario = $_SESSION['usuario']['id'];

        //se recoge la entrada a partir del id
        $entrada  = EntradaBd::getEntrada($idEntrada);

        //si el usuario es el dueño del post, no debe darle like
        if ($entrada->getAutor() == $idUsuario) {
            $this->vista = "errores/403";
            return null;
        }

        //se crea un nuevo megusta con el id del usuario logueado
        $megusta = new Megusta(idUsuario: $idUsuario, idEntrada: $idEntrada);

        //si has dado me gusta ya, al darle otra vez lo quitas
        if (MeGustaBd::checkMeGusta($megusta->getIdUsuario(), $megusta->getIdEntrada())) {
            $id = MegustaBd::quitarMeGusta($megusta);
            $this->vista = "entrada/lista";
            return EntradaBd::getEntradas();
        }

        //se inserta en la base de datos
        $id = MegustaBd::darMeGusta($megusta);

        //devuelve a la vista  de lista de entradas, por lo que se llama al metodo getEntradas
        $this->vista = "entrada/lista";
        return EntradaBd::getEntradas();
    }

    public function pulsarMeGustaDesdeDetalle(): Entrada|null
    {
        //si no hay usuario logueado
        if (!$this->autenticado()) {
            return null;
        }

        //se recogen los datos
        $idEntrada = $_GET && isset($_GET['id']) ? $_GET['id'] : null;
        $idUsuario = $_SESSION['usuario']['id'];

        //se recoge la entrada a partir del id
        $entrada  = EntradaBd::getEntrada($idEntrada);

        //si el usuario es el dueño del post, no debe darle like
        if ($entrada->getAutor() == $idUsuario) {
            $this->vista = "errores/403";
            return null;
        }

        //se crea un nuevo megusta con el id del usuario logueado
        $megusta = new Megusta(idUsuario: $idUsuario, idEntrada: $idEntrada);

        //si has dado me gusta ya, al darle otra vez lo quitas
        if (MeGustaBd::checkMeGusta($megusta->getIdUsuario(), $megusta->getIdEntrada())) {
            $id = MegustaBd::quitarMeGusta($megusta);
            $this->vista = "entrada/detalle";
            return EntradaBd::getEntrada($idEntrada);
        }
        //se inserta en la base de datos
        $id = MegustaBd::darMeGusta($megusta);

        //devuelve a la vista  de lista de entradas, por lo que se llama al metodo getEntradas
        $this->vista = "entrada/detalle";
        return EntradaBd::getEntrada($idEntrada);
    }
}
