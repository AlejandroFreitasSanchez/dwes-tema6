<?php

namespace dwesgram\controlador;
use dwesgram\utilidades\Sesion;
abstract class Controlador
{
    protected string|null $vista = null;

    public function getVista()
    {
        return $this->vista;
    }

    public function autenticado(): bool
    {
        $sesion = new Sesion();
        if (!$sesion->haySesion()) {
            $this->vista = 'errores/403';
            return false;
        }
        return true;
    }
}
