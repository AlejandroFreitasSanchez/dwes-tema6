<?

namespace dwesgram\modelo;

use dwesgram\modelo\Modelo;

class Comentario
{
    public function __construct(
        private int|null $idUsuario,
        private int|null $idEntrada,
        private String|null $texto
    ) {
    }

    public function getIdUsuario(): int|null
    {
        return $this->idUsuario;
    }
    public function getIdEntrada(): int|null
    {
        return $this->idEntrada;
    }
    public function getTexto(): String|null
    {
        return $this->texto;
    }
}
