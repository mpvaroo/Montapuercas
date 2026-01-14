<?php
class ReservaLogEstado
{
    private $id;
    private $reserva_id;
    private $estado_anterior;
    private $estado_nuevo;
    private $cambiado_por_usuario_id;
    private $detalle;
    private $creado_en;

    public function __construct($id, $reserva_id, $estado_anterior, $estado_nuevo, $cambiado_por_usuario_id, $detalle, $creado_en)
    {
        $this->id = $id;
        $this->reserva_id = $reserva_id;
        $this->estado_anterior = $estado_anterior;
        $this->estado_nuevo = $estado_nuevo;
        $this->cambiado_por_usuario_id = $cambiado_por_usuario_id;
        $this->detalle = $detalle;
        $this->creado_en = $creado_en;
    }
    public function getId()
    {
        return $this->id;
    }
    public function getReserva_id()
    {
        return $this->reserva_id;
    }
    public function getEstado_anterior()
    {
        return $this->estado_anterior;
    }
    public function getEstado_nuevo()
    {
        return $this->estado_nuevo;
    }
    public function getCambiado_por_usuario_id()
    {
        return $this->cambiado_por_usuario_id;
    }
    public function getDetalle()
    {
        return $this->detalle;
    }
    public function getCreado_en()
    {
        return $this->creado_en;
    }
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setReserva_id($reserva_id)
    {
        $this->reserva_id = $reserva_id;
    }
    public function setEstado_anterior($estado_anterior)
    {
        $this->estado_anterior = $estado_anterior;
    }
    public function setEstado_nuevo($estado_nuevo)
    {
        $this->estado_nuevo = $estado_nuevo;
    }
    public function setCambiado_por_usuario_id($cambiado_por_usuario_id)
    {
        $this->cambiado_por_usuario_id = $cambiado_por_usuario_id;
    }
    public function setDetalle($detalle)
    {
        $this->detalle = $detalle;
    }
    public function setCreado_en($creado_en)
    {
        $this->creado_en = $creado_en;
    }
    public function __toString()
    {
        return "Cambio de estado {$this->id}: de {$this->estado_anterior} a {$this->estado_nuevo}";
    }
}
