<?php

class IAExtraccion
{
    private $id;
    private $gimnasio_id;
    private $socio_id;
    private $sesion_id;
    private $tipo;
    private $texto_usuario;
    private $json_resultado;
    private $confianza_pct;
    private $estado;
    private $errores;
    private $creado_en;

    public function __construct(
        $id,
        $gimnasio_id,
        $socio_id,
        $sesion_id,
        $tipo,
        $texto_usuario,
        $json_resultado,
        $confianza_pct,
        $estado,
        $errores,
        $creado_en
    ) {
        $this->id = $id;
        $this->gimnasio_id = $gimnasio_id;
        $this->socio_id = $socio_id;
        $this->sesion_id = $sesion_id;
        $this->tipo = $tipo;
        $this->texto_usuario = $texto_usuario;
        $this->json_resultado = $json_resultado;
        $this->confianza_pct = $confianza_pct;
        $this->estado = $estado;
        $this->errores = $errores;
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
    public function getSesion_id()
    {
        return $this->sesion_id;
    }
    public function getTipo()
    {
        return $this->tipo;
    }
    public function getTexto_usuario()
    {
        return $this->texto_usuario;
    }
    public function getJson_resultado()
    {
        return $this->json_resultado;
    }
    public function getConfianza_pct()
    {
        return $this->confianza_pct;
    }
    public function getEstado()
    {
        return $this->estado;
    }
    public function getErrores()
    {
        return $this->errores;
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
    public function setSesion_id($sesion_id)
    {
        $this->sesion_id = $sesion_id;
    }
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }
    public function setTexto_usuario($texto_usuario)
    {
        $this->texto_usuario = $texto_usuario;
    }
    public function setJson_resultado($json_resultado)
    {
        $this->json_resultado = $json_resultado;
    }
    public function setConfianza_pct($confianza_pct)
    {
        $this->confianza_pct = $confianza_pct;
    }
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }
    public function setErrores($errores)
    {
        $this->errores = $errores;
    }
    public function setCreado_en($creado_en)
    {
        $this->creado_en = $creado_en;
    }
    public function __toString()
    {
        return "Extracción {$this->id}: tipo {$this->tipo} con confianza {$this->confianza_pct}%";
    }
}
