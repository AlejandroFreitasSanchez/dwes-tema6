<?php

namespace dwesgram\modelo;

use dwesgram\modelo\BaseDatos;
use dwesgram\modelo\Entrada as ModeloEntrada;


class EntradaBd
{

    use BaseDatos;

    public static function getEntradas(): array
    {
        try {
            $resultado = [];
            $conexion = BaseDatos::getConexion();
            //primera consulta para obtener la entrada
            $queryResultado = $conexion->query("select id, texto, imagen, autor, creado from Entrada order by creado");
            if ($queryResultado !== false) {
                while (($fila = $queryResultado->fetch_assoc()) != null) {
                    //segunda consulta para obtener el nombre del autor
                    $queryResultado2 = $conexion->query("Select nombre from usuario u join entrada e where u.id='{$fila['autor']}' ");

                    if ($queryResultado2 !== false) {
                        $fila2 = $queryResultado2->fetch_assoc();

                        $entrada = new ModeloEntrada(
                            id: $fila['id'],
                            texto: $fila['texto'],
                            imagen: $fila['imagen'],
                            autor: $fila['autor'],
                            nombreAutor: $fila2['nombre'],
                            creado: $fila['creado'],

                        );
                        //tercera query para obtener el numer de likes, esta dentro del anterior if, ya que este no tiene por que tener likes
                        $queryResultado3 = $conexion->query("select entrada, count(usuario) from megusta where entrada = '{$fila['id']}' ");
                        if ($queryResultado3 != false) {
                            $fila3 = $queryResultado3->fetch_assoc();
                            $entrada->setMegustas($fila3['count(usuario)']);
                        }

                        $resultado[] = $entrada;
                    }
                }
            }

            return $resultado;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return [];
        }
    }



    public static function getEntrada(int $id): ModeloEntrada|null
    {
        try {
            $conexion = BaseDatos::getConexion();
            //primera consulta para obtener la entrada
            $sentencia = $conexion->prepare("select id, texto, imagen, autor, creado from Entrada where id=?");
            $sentencia->bind_param('i', $id);
            $sentencia->execute();
            $resultado = $sentencia->get_result();
            $fila = $resultado->fetch_assoc();
            if ($fila == null) {
                return null;
            } else {
                //segunda consulta para obtener el nombre del autor
                $queryResultado2 = $conexion->query("Select nombre from usuario u join entrada e where u.id='{$fila['autor']}' ");
                if ($queryResultado2 !== false) {
                    $fila2 = $queryResultado2->fetch_assoc();
                    $entrada = new ModeloEntrada(
                        id: $fila['id'],
                        texto: $fila['texto'],
                        imagen: $fila['imagen'],
                        autor: $fila['autor'],
                        nombreAutor: $fila2['nombre'],
                        creado: $fila['creado'],

                    );
                    //tercera query para obtener el numer de likes, esta dentro del anterior if, ya que este no tiene por que tener likes
                    $queryResultado3 = $conexion->query("select entrada, count(usuario) from megusta where entrada = '{$fila['id']}' ");
                    if ($queryResultado3 != false) {
                        $fila3 = $queryResultado3->fetch_assoc();
                        $entrada->setMegustas($fila3['count(usuario)']);
                    }

                    return $entrada;
                    
                }
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            return null;
        }
    }

    public static function insertar(ModeloEntrada $entrada): int|null
    {
        try {
            $conexion = BaseDatos::getConexion();
            $sentencia = $conexion->prepare("insert into entrada (texto, imagen, autor) values (?, ?, ?)");
            $texto = $entrada->getTexto();
            $imagen = $entrada->getImagen();
            $autor = $_SESSION['usuario']['id'];
            $sentencia->bind_param('ssi', $texto, $imagen, $autor);
            $sentencia->execute();
            return $conexion->insert_id;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return null;
        }
    }

    public static function eliminar(int $id): bool
    {
        try {
            $conexion = BaseDatos::getConexion();
            $sentencia = $conexion->prepare("delete from entrada where id = ?");
            $sentencia->bind_param('i', $id);
            return $sentencia->execute();
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }
}
