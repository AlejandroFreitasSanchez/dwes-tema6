<?php

namespace dwesgram\modelo;

use dwesgram\modelo\BaseDatos;
use dwesgram\modelo\Usuario;

class UsuarioBd
{

    use BaseDatos;

    public static function getUsuarioPorNombre(string $nombre): Usuario|null
    {
        try {
            $conexion = BaseDatos::getConexion();
            $sentencia = $conexion->prepare("select id, nombre, clave, email, avatar from usuario where nombre=?");
            $sentencia->bind_param('s', $nombre);
            $sentencia->execute();
            $resultado = $sentencia->get_result();
            $fila = $resultado->fetch_assoc();
            if ($fila == null) {
                return null;
            } else {
                return new Usuario(
                    nombre: $fila['nombre'],
                    clave: $fila['clave'],
                    email: $fila['email'],
                    avatar: $fila['avatar'],
                    id: $fila['id']
                );
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            return null;
        }
    }
    
    public static function insertar(Usuario $usuario): int|null
    {
        try {
            $conexion = BaseDatos::getConexion();
            $sentencia = $conexion->prepare("insert into usuario (nombre, clave, email, avatar) values (?, ?, ?, ?)");
            $nombre = $usuario->getNombre();
            $email = $usuario->getEmail();
            $clave = password_hash($usuario->getClave(), PASSWORD_BCRYPT);
            $avatar = $usuario->getAvatar();
            $sentencia->bind_param('ssss', $nombre, $clave, $email, $avatar);
            $sentencia->execute();
            return $conexion->insert_id;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return null;
        }
    }
}
