<?php

namespace dwesgram\controlador;

use dwesgram\controlador\Controlador;
use dwesgram\modelo\Usuario;
use dwesgram\modelo\UsuarioBd;

class UsuarioControlador extends Controlador
{

    public function login(): Usuario|null|String
    {
        //si hay usuario logueado
        if ($this->autenticado()) {
            header('Location: index.php');
            return null;
        }
        //si no hay post
        if (!$_POST) {
            $this->vista = 'usuario/login';
            return null;
        }

        $nombre = $_POST && isset($_POST['nombre']) ? $_POST['nombre'] : "";
        $clave = $_POST && isset($_POST['clave']) ? $_POST['clave'] : "";
        $usuario = UsuarioBd::getUsuarioPorNombre($nombre);

        //si el usuario existe, se comprueba que las contraseñas coincidadn
        if ($usuario === null) {
            $this->vista = "usuario/mensaje";
            return "Error: El usuario no existe o la contraseña no es valida";
        }
        //si las contraseñas coinciden, se loguea
        if (password_verify($clave, $usuario->getClave())) {
            $_SESSION['usuario'] = [
                'id' => $usuario->getId(),
                'nombre' => $usuario->getNombre(),
                'email' => $usuario->getEmail(),
                'avatar' => $usuario->getAvatar(),
            ];
            header('Location: index.php');
            return null;
        } else {
            $this->vista = "usuario/mensaje";
            return "Error: El usuario no existe o la contraseña no es valida";
        }
    }
    public function registro(): Usuario|null|String
    {
        //si hay usuario logueado
        if ($this->autenticado()) {
            header('Location: index.php');
            return null;
        }
        //si no hay post
        if (!$_POST) {
            $this->vista = 'usuario/registro';
            return null;
        }
        
        $usuario = Usuario::crearUsuarioDesdePost($_POST);
        if ($usuario->validarAvatarDesdePost($_FILES) === false) {
            $this->vista = 'usuario/registro';
            return $usuario;
        }
        if (!$usuario->esValido()) {
            $this->vista = 'usuario/registro';
            return $usuario;
        }

        

        //insertamos el usuario en la base de datos
        $id = UsuarioBd::insertar($usuario);
        if ($id !== null) {
            $this->vista = "usuario/mensaje";
            return "The has registrado correctamente... Ya puedes iniciar sesion";
        } else {
            $this->vista = "usuario/mensaje";
            return "Error: No se ha podido llevar a cabo el registro, prueba más tarde";
        }
        return $usuario;
    }

    public function logout(): void
    {
        //si no hay usuario logueado
        if (!$this->autenticado()) {
            return ;
        }
        session_destroy();
        header("Location: index.php");
    }
}
