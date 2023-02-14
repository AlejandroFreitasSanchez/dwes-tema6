<?

namespace dwesgram\modelo;

use dwesgram\modelo\BaseDatos;
use dwesgram\modelo\Megusta as ModeloMegusta;

class MegustaBd
{

    use BaseDatos;

    public static function darMeGusta(ModeloMegusta $megusta): int|null
    {
        try {
            $conexion = BaseDatos::getConexion();
            $sentencia = $conexion->prepare("insert into megusta (entrada, usuario) values (?, ?)");
            $idUsuario = $megusta->getIdUsuario();
            $idEntrada = $megusta->getIdEntrada();
            $sentencia->bind_param('ii', $idEntrada, $idUsuario);
            $sentencia->execute();
            return $conexion->insert_id;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return null;
        }
    }
    
    public static function quitarMeGusta(ModeloMegusta $megusta): bool
    {
        try {
            $conexion = BaseDatos::getConexion();
            $sentencia = $conexion->prepare("delete from megusta where usuario = ? and entrada = ?");
            $idUsuario = $megusta->getIdUsuario();
            $idEntrada = $megusta->getIdEntrada();
            $sentencia->bind_param('ii', $idUsuario, $idEntrada);
            return $sentencia->execute();
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    //comprueba si un usuario ya ha dado like a una entrada
    public static function checkMeGusta($idUsuario, $idEntrada): bool|null
    {
        try {

            $conexion = BaseDatos::getConexion();
            $sentencia = $conexion->prepare("select * from megusta where usuario = ? and entrada = ?");
            $sentencia->bind_param("ii", $idUsuario, $idEntrada);
            $sentencia->execute();
            $resultado = $sentencia->get_result();
            if ($resultado->num_rows == 0) {
                return false;
            } else {
                return true;
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            return null;
        }
    }
}
