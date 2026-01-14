<?php
class Actividad
{
    private $id;
    private $gimnasio_id;
    private $nombre;
    private $descripcion;
    private $duracion_min;
    private $aforo;
    private $activa;
    private $creado_en;

    public function __construct($id, $gimnasio_id, $nombre, $descripcion, $duracion_min, $aforo, $activa, $creado_en)
    {
        $this->id = $id;
        $this->gimnasio_id = $gimnasio_id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->duracion_min = $duracion_min;
        $this->aforo = $aforo;
        $this->activa = $activa;
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
    public function getAforo()
    {
        return $this->aforo;
    }
    public function getActiva()
    {
        return $this->activa;
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
    public function setAforo($aforo)
    {
        $this->aforo = $aforo;
    }
    public function setActiva($activa)
    {
        $this->activa = $activa;
    }
    public function setCreado_en($creado_en)
    {
        $this->creado_en = $creado_en;
    }
    public function __toString()
    {
        return "Actividad: {$this->nombre} ({$this->duracion_min} min)";
    }
}
