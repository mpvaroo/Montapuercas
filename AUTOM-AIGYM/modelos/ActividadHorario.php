<?php

class ActividadHorario
{
    private $id;
    private $gimnasio_id;
    private $actividad_id;
    private $monitor_usuario_id;
    private $dia_semana;
    private $hora_inicio;
    private $duracion_min;
    private $aforo;
    private $activo;
    private $creado_en;

    public function __construct($id, $gimnasio_id, $actividad_id, $monitor_usuario_id, $dia_semana, $hora_inicio, $duracion_min, $aforo, $activo, $creado_en)
    {
        $this->id = $id;
        $this->gimnasio_id = $gimnasio_id;
        $this->actividad_id = $actividad_id;
        $this->monitor_usuario_id = $monitor_usuario_id;
        $this->dia_semana = $dia_semana;
        $this->hora_inicio = $hora_inicio;
        $this->duracion_min = $duracion_min;
        $this->aforo = $aforo;
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
    public function getActividad_id()
    {
        return $this->actividad_id;
    }
    public function getMonitor_usuario_id()
    {
        return $this->monitor_usuario_id;
    }
    public function getDia_semana()
    {
        return $this->dia_semana;
    }
    public function getHora_inicio()
    {
        return $this->hora_inicio;
    }
    public function getDuracion_min()
    {
        return $this->duracion_min;
    }
    public function getAforo()
    {
        return $this->aforo;
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
    public function setActividad_id($actividad_id)
    {
        $this->actividad_id = $actividad_id;
    }
    public function setMonitor_usuario_id($monitor_usuario_id)
    {
        $this->monitor_usuario_id = $monitor_usuario_id;
    }
    public function setDia_semana($dia_semana)
    {
        $this->dia_semana = $dia_semana;
    }
    public function setHora_inicio($hora_inicio)
    {
        $this->hora_inicio = $hora_inicio;
    }
    public function setDuracion_min($duracion_min)
    {
        $this->duracion_min = $duracion_min;
    }
    public function setAforo($aforo)
    {
        $this->aforo = $aforo;
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
        return "Horario {$this->id}: día {$this->dia_semana}, hora {$this->hora_inicio}, actividad {$this->actividad_id}";
    }
}
