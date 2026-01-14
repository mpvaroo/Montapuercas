<?php
class Servicio
{
    private $id;
    private $gimnasio_id;
    private $nombre;
    private $descripcion;
    private $duracion_min;
    private $precio;
    private $activo;
    private $creado_en;

    public function __construct($id, $gimnasio_id, $nombre, $descripcion, $duracion_min, $precio, $activo, $creado_en)
    {
        $this->id = $id;
        $this->gimnasio_id = $gimnasio_id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->duracion_min = $duracion_min;
        $this->precio = $precio;
        $this->activo = $activo;
        $this->creado_en = $creado_en;
    }
    public function getId()
    {
        return $this->id;
    }
    public function getGimnasio_id()
    {
        return $this->gimnasio_id;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function getDescripcion()
    {
        return $this->descripcion;
    }
    public function getDuracion_min()
    {
        return $this->duracion_min;
    }
    public function getPrecio()
    {
        return $this->precio;
    }
    public function getActivo()
    {
        return $this->activo;
    }
    public function getCreado_en()
    {
        return $this->creado_en;
    }
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setGimnasio_id($gimnasio_id)
    {
        $this->gimnasio_id = $gimnasio_id;
    }
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }
    public function setDuracion_min($duracion_min)
    {
        $this->duracion_min = $duracion_min;
    }
    public function setPrecio($precio)
    {
        $this->precio = $precio;
    }
    public function setActivo($activo)
    {
        $this->activo = $activo;
    }
    public function setCreado_en($creado_en)
    {
        $this->creado_en = $creado_en;
    }
    public function __toString()
    {
        return "Servicio: {$this->nombre} ({$this->duracion_min} min, Precio: {$this->precio})";
    }
}
