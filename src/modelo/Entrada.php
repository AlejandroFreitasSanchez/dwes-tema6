<?php

namespace dwesgram\modelo;

use dwesgram\modelo\Modelo;

class Entrada extends Modelo


{
    private array $errores = [];
    private array $comentarios = [];
    public function __construct(
        private string|null $texto,
        private int|null $id = null,
        private string|null $imagen = null,
        private int|null $autor = null,
        private string|null $nombreAutor = null,
        private int|null $creado = null,
        private int|null $megustas = null
    ) {
        $this->errores = [
            'texto' => $texto === null || empty($texto) ? 'El texto no puede estar vacío' : null,
            'imagen' => null,
            //'autor' => null,
            //'creado' => null,
        ];
        $this->comentarios = [];
        
    }
    public function validarImagenDesdePost(array $files): bool
    {
        if (
            $files && isset($files['imagen']) &&
            $files['imagen']['error'] === UPLOAD_ERR_OK
           
        ) {
            $nombreFichero = $files && isset($files['imagen']) ? $files['imagen']['name'] : null;
            //ruta donde se guardará la imagen
            $rutaFicheroDestino = 'imagenes/' . basename(time() . "." . pathinfo($nombreFichero, PATHINFO_EXTENSION));
            //fichero que le hemos pasado al formulario
            $ficheroFileInfo = $files['imagen'];
            //array de extensiones permitidas
            $permitido = array('image/png', 'image/jpeg');

            //si la extension no esta permitida, devuelve false
            if (!in_array(finfo_file(finfo_open(FILEINFO_MIME_TYPE), $ficheroFileInfo['tmp_name']), $permitido)) {
                $this->errores = ['imagen' => "La extension no esta permitida"];
                return false;
            } else {
                //si no, se guarda la imagen
                $seHaSubido = move_uploaded_file($files['imagen']['tmp_name'], $rutaFicheroDestino);
                $this->setImagen($rutaFicheroDestino);
                return true;
            }
        }
        // en este caso, es que no se ha seleccionado ninguna imagen, ya que es opcional
        $this->setImagen("imagenes/pordefecto.png");

        return true;
    }
    public function getId(): int|null
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTexto(): string
    {
        return $this->texto ? $this->texto : '';
    }

    public function getImagen(): string|null
    {
        return $this->imagen;
    }

    public function setImagen(string $img)
    {
        $this->imagen = $img;
    }

    public function getAutor(): int|null
    {
        return $this->autor;
    }

    public function getCreado(): int|null
    {
        return $this->creado;
    }
    public function getNombreAutor(): string|null
    {
        return $this->nombreAutor;
    }

    public function esValida(): bool
    {
        $valido = true;
        $errores = $this->getErrores();
        if ($errores['texto'] != null || $errores['imagen'] != null) {
            $valido = false;
        }

        return $valido;
    }

    public function getErrores(): array
    {
        return $this->errores;
    }
    public function getMegustas(): int
    {
        return $this->megustas;
    }
    public function setMegustas(int $id){
        $this->megustas = $id;
    }
    public function getComentarios(): array
    {
        return $this->comentarios;
    }
    public function setComentarios(array|null $c){
        $this->comentarios['comentarios'] = $c;
    }

}
