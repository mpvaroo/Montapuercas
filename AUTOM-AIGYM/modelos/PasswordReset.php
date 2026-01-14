
<?php
class PasswordReset
{
    private $id;
    private $tipo_cuenta;
    private $usuario_id;
    private $socio_id;
    private $token_hash;
    private $expira_en;
    private $usado;
    private $creado_en;

    public function __construct($id, $tipo_cuenta, $usuario_id, $socio_id, $token_hash, $expira_en, $usado, $creado_en)
    {
        $this->id = $id;
        $this->tipo_cuenta = $tipo_cuenta;
        $this->usuario_id = $usuario_id;
        $this->socio_id = $socio_id;
        $this->token_hash = $token_hash;
        $this->expira_en = $expira_en;
        $this->usado = $usado;
        $this->creado_en = $creado_en;
    }
    public function getId()
    {
        return $this->id;
    }
    public function getTipo_cuenta()
    {
        return $this->tipo_cuenta;
    }
    public function getUsuario_id()
    {
        return $this->usuario_id;
    }
    public function getSocio_id()
    {
        return $this->socio_id;
    }
    public function getToken_hash()
    {
        return $this->token_hash;
    }
    public function getExpira_en()
    {
        return $this->expira_en;
    }
    public function getUsado()
    {
        return $this->usado;
    }
    public function getCreado_en()
    {
        return $this->creado_en;
    }
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setTipo_cuenta($tipo_cuenta)
    {
        $this->tipo_cuenta = $tipo_cuenta;
    }
    public function setUsuario_id($usuario_id)
    {
        $this->usuario_id = $usuario_id;
    }
    public function setSocio_id($socio_id)
    {
        $this->socio_id = $socio_id;
    }
    public function setToken_hash($token_hash)
    {
        $this->token_hash = $token_hash;
    }
    public function setExpira_en($expira_en)
    {
        $this->expira_en = $expira_en;
    }
    public function setUsado($usado)
    {
        $this->usado = $usado;
    }
    public function setCreado_en($creado_en)
    {
        $this->creado_en = $creado_en;
    }
    public function __toString()
    {
        return "PasswordReset {$this->id}: para {$this->tipo_cuenta}, expiración {$this->expira_en}";
    }
}

?>