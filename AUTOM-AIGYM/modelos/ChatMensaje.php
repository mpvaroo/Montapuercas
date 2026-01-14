<?php
class ChatMensaje
{
    private $id;
    private $sesion_id;
    private $rol;
    private $mensaje;
    private $creado_en;

    public function __construct($id, $sesion_id, $rol, $mensaje, $creado_en)
    {
        $this->id = $id;
        $this->sesion_id = $sesion_id;
        $this->rol = $rol;
        $this->mensaje = $mensaje;
        $this->creado_en = $creado_en;
    }
    public function getId()
    {
        return $this->id;
    }
    public function getSesion_id()
    {
        return $this->sesion_id;
    }
    public function getRol()
    {
        return $this->rol;
    }
    public function getMensaje()
    {
        return $this->mensaje;
    }
    public function getCreado_en()
    {
        return $this->creado_en;
    }
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setSesion_id($sesion_id)
    {
        $this->sesion_id = $sesion_id;
    }
    public function setRol($rol)
    {
        $this->rol = $rol;
    }
    public function setMensaje($mensaje)
    {
        $this->mensaje = $mensaje;
    }
    public function setCreado_en($creado_en)
    {
        $this->creado_en = $creado_en;
    }
    public function __toString()
    {
        return "Mensaje {$this->id}: {$this->rol} dice \"{$this->mensaje}\"";
    }
}
