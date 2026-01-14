<?php
class Reserva
{
    private $id;
    private $gimnasio_id;
    private $socio_id;
    private $tipo;
    private $actividad_horario_id;
    private $servicio_id;
    private $monitor_usuario_id;
    private $fecha;
    private $hora;
    private $duracion_min;
    private $estado;
    private $creada_por;
    private $notas;
    private $creado_en;
    private $actualizado_en;

    public function __construct(
        $id,
        $gimnasio_id,
        $socio_id,
        $tipo,
        $actividad_horario_id,
        $servicio_id,
        $monitor_usuario_id,
        $fecha,
        $hora,
        $duracion_min,
        $estado,
        $creada_por,
        $notas,
        $creado_en,
        $actualizado_en
    ) {
        $this->id = $id;
        $this->gimnasio_id = $gimnasio_id;
        $this->socio_id = $socio_id;
        $this->tipo = $tipo;
        $this->actividad_horario_id = $actividad_horario_id;
        $this->servicio_id = $servicio_id;
        $this->monitor_usuario_id = $monitor_usuario_id;
        $this->fecha = $fecha;
        $this->hora = $hora;
        $this->duracion_min = $duracion_min;
        $this->estado = $estado;
        $this->creada_por = $creada_por;
        $this->notas = $notas;
        $this->creado_en = $creado_en;
        $this->actualizado_en = $actualizado_en;
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
    public function getTipo()
    {
        return $this->tipo;
    }
    public function getActividad_horario_id()
    {
        return $this->actividad_horario_id;
    }
    public function getServicio_id()
    {
        return $this->servicio_id;
    }
    public function getMonitor_usuario_id()
    {
        return $this->monitor_usuario_id;
    }
    public function getFecha()
    {
        return $this->fecha;
    }
    public function getHora()
    {
        return $this->hora;
    }
    public function getDuracion_min()
    {
        return $this->duracion_min;
    }
    public function getEstado()
    {
        return $this->estado;
    }
    public function getCreada_por()
    {
        return $this->creada_por;
    }
    public function getNotas()
    {
        return $this->notas;
    }
    public function getCreado_en()
    {
        return $this->creado_en;
    }
    public function getActualizado_en()
    {
        return $this->actualizado_en;
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
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }
    public function setActividad_horario_id($actividad_horario_id)
    {
        $this->actividad_horario_id = $actividad_horario_id;
    }
    public function setServicio_id($servicio_id)
    {
        $this->servicio_id = $servicio_id;
    }
    public function setMonitor_usuario_id($monitor_usuario_id)
    {
        $this->monitor_usuario_id = $monitor_usuario_id;
    }
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }
    public function setHora($hora)
    {
        $this->hora = $hora;
    }
    public function setDuracion_min($duracion_min)
    {
        $this->duracion_min = $duracion_min;
    }
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }
    public function setCreada_por($creada_por)
    {
        $this->creada_por = $creada_por;
    }
    public function setNotas($notas)
    {
        $this->notas = $notas;
    }
    public function setCreado_en($creado_en)
    {
        $this->creado_en = $creado_en;
    }
    public function setActualizado_en($actualizado_en)
    {
        $this->actualizado_en = $actualizado_en;
    }
    public function __toString()
    {
        return "Reserva {$this->id}: {$this->tipo} el {$this->fecha} a las {$this->hora} (Estado: {$this->estado})";
    }
}
