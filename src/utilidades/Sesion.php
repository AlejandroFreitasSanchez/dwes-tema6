<?php

namespace dwesgram\utilidades;



class Sesion
{
    private int|null $id;
    private string|null $nombre;
    private string|null $email;
    private string|null $avatar;
    public function __construct()
    {
        $this->id = $_SESSION && isset($_SESSION['usuario']) && isset($_SESSION['usuario']['id']) ? htmlspecialchars($_SESSION['usuario']['id']) : null;
        $this->nombre = $_SESSION && isset($_SESSION['usuario']) && isset($_SESSION['usuario']['nombre']) ? htmlspecialchars($_SESSION['usuario']['nombre']) : null;
        $this->email = $_SESSION && isset($_SESSION['usuario']) && isset($_SESSION['usuario']['email']) ? htmlspecialchars($_SESSION['usuario']['email']) : null;
        $this->avatar = $_SESSION && isset($_SESSION['usuario']) && isset($_SESSION['usuario']['avatar']) ? htmlspecialchars($_SESSION['usuario']['avatar']) : null;
    }

    public function haySesion(){
        return $this->id !== null && $this->nombre !== null ? true: false;
    }
}
