<?php

namespace dwesgram\modelo;

use dwesgram\modelo\Modelo;

class Usuario extends Modelo
{
    private array $errores = [];
    public function __construct(
        private string|null $nombre,
        private string|null $clave,
        private string|null $email,
        private int|null $id = null,
        private string|null $avatar = null,
        private int|null $registrado = null
    ) {
        $this->errores = [
            'nombre' => $nombre === null || empty($nombre) ? 'El texto no puede estar vacío' : null,
            'clave' => $clave === null || empty($clave) ? 'La clave no puede estar vacia' : null,
            'clave' => strlen($clave) < 8 ? 'La clave debe tener al menos 8 caracteres' : null,
            'email' => $email === null || empty($email) ? 'El email no puede estar vacio' : null,
            'repiteclave' => null,
            'avatar' => null,
            //'autor' => null,
            //'creado' => null,
        ];
    }


    public static function crearUsuarioDesdePost(array $post): Usuario|null
    {
        $nombre = $post && isset($post['nombre']) ? htmlspecialchars($post['nombre']) : null;
        $email = $post && isset($post['email']) ? htmlspecialchars($post['email']) : null;
        $clave = $post && isset($post['clave']) ? htmlspecialchars($post['clave']) : null;
        $repiteclave = $post && isset($post['repiteclave']) ? htmlspecialchars($post['repiteclave']) : null;
        
        $usuario = new Usuario(nombre: $nombre, clave: $clave, email: $email, registrado: time());
        if (strlen($clave) > 8 && $clave != $repiteclave) {
            $usuario->errores = [
                'repiteclave' => "Las contraseñas no coinciden"
            ];
        }
        
        return $usuario;
    }

    public function validarAvatarDesdePost(array $files): bool
    {
        if (
            $files && isset($files['avatar']) && $files['avatar']['error'] === UPLOAD_ERR_OK
        ) {
            $nombreFichero = $files && isset($files['avatar']) ? $files['avatar']['name'] : null;
            //ruta donde se guardará la imagen
            $rutaFicheroDestino = 'imagenes/' . basename(time() . "." . pathinfo($nombreFichero, PATHINFO_EXTENSION));
            //fichero que le hemos pasado al formulario
            $ficheroFileInfo = $files['avatar'];
            //array de extensiones permitidas
            $permitido = array('image/png', 'image/jpeg');

            //si la extension no esta permitida, devuelve false
            if (!in_array(finfo_file(finfo_open(FILEINFO_MIME_TYPE), $ficheroFileInfo['tmp_name']), $permitido)) {
                $this->errores =  ['avatar' => "La extension no esta permitida"];
                return false;
            } else {
                //si no, se guarda la imagen
                $seHaSubido = move_uploaded_file($files['avatar']['tmp_name'], $rutaFicheroDestino);
                $this->setAvatar($rutaFicheroDestino);
                return true;
            }
        }
        // en este caso, es que no se ha seleccionado ninguna imagen, ya que es opcional
        $this->setAvatar("imagenes/pordefecto.webp");

        return true;
    }
    public function getNombre(): string|null
    {
        return $this->nombre;
    }
    public function getErrores(): array
    {
        return $this->errores;
    }

    public function getId(): int|null
    {
        return $this->id;
    }

    public function getClave(): string|null
    {
        return $this->clave;
    }
    public function getEmail(): string|null
    {
        return $this->email;
    }

    public function getAvatar(): string|null
    {
        return $this->avatar;
    }

    public function setAvatar(String $avatar){
        $this->avatar=$avatar;
    }
    public function getRegistrado(): int|null
    {
        return $this->registrado;
    }

    public function esValido(): bool
    {
        $valido = true;
        $errores = $this->getErrores();
        if ($errores['nombre'] != null || $errores['clave'] != null || $errores['email'] != null || $errores['repiteclave'] != null || $errores['avatar'] !=null) {
            $valido = false;
        }

        return $valido;
    }
}
