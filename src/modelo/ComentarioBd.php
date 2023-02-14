<?

namespace dwesgram\modelo;

use dwesgram\modelo\BaseDatos;
use dwesgram\modelo\Comentario as ModeloComentario;

class ComentarioBd
{

    use BaseDatos;

    public static function insertar(ModeloComentario $comentario): int|null
    {
        try {
            $conexion = BaseDatos::getConexion();
            $sentencia = $conexion->prepare("insert into comentario (comentario, usuario, entrada) values (?, ?, ?) ON DUPLICATE KEY UPDATE comentario = ?");
            $idUsuario = $comentario->getIdUsuario();
            $idEntrada = $comentario->getIdEntrada();
            $texto = $comentario->getTexto();
            $sentencia->bind_param('siis', $texto, $idUsuario, $idEntrada, $texto);
            $sentencia->execute();
            return $conexion->insert_id;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return null;
        }
    }
}
