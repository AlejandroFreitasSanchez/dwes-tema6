<?

namespace dwesgram\modelo;

use dwesgram\modelo\Modelo;

class Megusta
{
    public function __construct(
        private int|null $idUsuario,
        private int|null $idEntrada,
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
}
