<?php

namespace models;

class Funko
{
    public static $IMAGEN_DEFAULT = 'https://via.placeholder.com/150';
    private $id;
    private $nombre;
    private $imagen;
    private $precio;
    private $cantidad;
    private $createdAt;
    private $updatedAt;
    private $categoriaId;
    private $categoriaNombre;
    private $isDeleted;

    // Constructor con parámetros opcionales
    public function __construct($id = null, $nombre = null, $imagen = null, $precio = null, $cantidad = null, $createdAt = null, $updatedAt = null, $categoriaId = null, $categoriaNombre = null, $isDeleted = null)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->imagen = $imagen ?? self::$IMAGEN_DEFAULT;
        $this->precio = $precio;
        $this->cantidad = $cantidad;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->categoriaId = $categoriaId;
        $this->categoriaNombre = $categoriaNombre;
        $this->isDeleted = $isDeleted;
    }

    // Magic method for get and set
    public function __get($name)
    {
        return $this->$name;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }
}