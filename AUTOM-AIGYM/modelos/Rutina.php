<?php
class Rutina
{
    private $id;
    private $gimnasio_id;
    private $socio_id;
    private $nombre;
    private $objetivo;
    private $nivel;
    private $duracion_min;
    private $origen;
    private $notas_generales;
    private $creado_en;

    public function __construct($id, $gimnasio_id, $socio_id, $nombre, $objetivo, $nivel, $duracion_min, $origen, $notas_generales, $creado_en)
    {
        $this->id = $id;
        $this->gimnasio_id = $gimnasio_id;
        $this->socio_id = $socio_id;
        $this->nombre = $nombre;
        $this->objetivo = $objetivo;
        $this->nivel = $nivel;
        $this->duracion_min = $duracion_min;
        $this->origen = $origen;
        $this->notas_generales = $notas_generales;
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
    public function getSocio_id()
    {
        return $this->socio_id;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function getObjetivo()
    {
        return $this->objetivo;
    }
    public function getNivel()
    {
        return $this->nivel;
    }
    public function getDuracion_min()
    {
        return $this->duracion_min;
    }
    public function getOrigen()
    {
        return $this->origen;
    }
    public function getNotas_generales()
    {
        return $this->notas_generales;
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
    public function setSocio_id($socio_id)
    {
        $this->socio_id = $socio_id;
    }
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function setObjetivo($objetivo)
    {
        $this->objetivo = $objetivo;
    }
    public function setNivel($nivel)
    {
        $this->nivel = $nivel;
    }
    public function setDuracion_min($duracion_min)
    {
        $this->duracion_min = $duracion_min;
    }
    public function setOrigen($origen)
    {
        $this->origen = $origen;
    }
    public function setNotas_generales($notas_generales)
    {
        $this->notas_generales = $notas_generales;
    }
    public function setCreado_en($creado_en)
    {
        $this->creado_en = $creado_en;
    }
    public function __toString()
    {
        return "Rutina {$this->id}: {$this->nombre} ({$this->objetivo}, {$this->nivel})";
    }
}
