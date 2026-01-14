
<?php
class ChatSesion
{
    private $id;
    private $gimnasio_id;
    private $socio_id;
    private $canal;
    private $estado;
    private $creado_en;
    private $actualizado_en;

    public function __construct($id, $gimnasio_id, $socio_id, $canal, $estado, $creado_en, $actualizado_en)
    {
        $this->id = $id;
        $this->gimnasio_id = $gimnasio_id;
        $this->socio_id = $socio_id;
        $this->canal = $canal;
        $this->estado = $estado;
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
    public function getCanal()
    {
        return $this->canal;
    }
    public function getEstado()
    {
        return $this->estado;
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
    public function setCanal($canal)
    {
        $this->canal = $canal;
    }
    public function setEstado($estado)
    {
        $this->estado = $estado;
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
        return "Chat sesión {$this->id} (canal {$this->canal})";
    }
}
