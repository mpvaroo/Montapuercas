<?php

class ExportJob
{
    private $id;
    private $gimnasio_id;
    private $usuario_id;
    private $tipo;
    private $formato;
    private $estado;
    private $fecha_desde;
    private $fecha_hasta;
    private $nombre_archivo;
    private $ruta_archivo;
    private $tamano_bytes;
    private $error_msg;
    private $creado_en;
    private $actualizado_en;

    public function __construct(
        $id,
        $gimnasio_id,
        $usuario_id,
        $tipo,
        $formato,
        $estado,
        $fecha_desde,
        $fecha_hasta,
        $nombre_archivo,
        $ruta_archivo,
        $tamano_bytes,
        $error_msg,
        $creado_en,
        $actualizado_en
    ) {
        $this->id = $id;
        $this->gimnasio_id = $gimnasio_id;
        $this->usuario_id = $usuario_id;
        $this->tipo = $tipo;
        $this->formato = $formato;
        $this->estado = $estado;
        $this->fecha_desde = $fecha_desde;
        $this->fecha_hasta = $fecha_hasta;
        $this->nombre_archivo = $nombre_archivo;
        $this->ruta_archivo = $ruta_archivo;
        $this->tamano_bytes = $tamano_bytes;
        $this->error_msg = $error_msg;
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
    public function getUsuario_id()
    {
        return $this->usuario_id;
    }
    public function getTipo()
    {
        return $this->tipo;
    }
    public function getFormato()
    {
        return $this->formato;
    }
    public function getEstado()
    {
        return $this->estado;
    }
    public function getFecha_desde()
    {
        return $this->fecha_desde;
    }
    public function getFecha_hasta()
    {
        return $this->fecha_hasta;
    }
    public function getNombre_archivo()
    {
        return $this->nombre_archivo;
    }
    public function getRuta_archivo()
    {
        return $this->ruta_archivo;
    }
    public function getTamano_bytes()
    {
        return $this->tamano_bytes;
    }
    public function getError_msg()
    {
        return $this->error_msg;
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
    public function setUsuario_id($usuario_id)
    {
        $this->usuario_id = $usuario_id;
    }
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }
    public function setFormato($formato)
    {
        $this->formato = $formato;
    }
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }
    public function setFecha_desde($fecha_desde)
    {
        $this->fecha_desde = $fecha_desde;
    }
    public function setFecha_hasta($fecha_hasta)
    {
        $this->fecha_hasta = $fecha_hasta;
    }
    public function setNombre_archivo($nombre_archivo)
    {
        $this->nombre_archivo = $nombre_archivo;
    }
    public function setRuta_archivo($ruta_archivo)
    {
        $this->ruta_archivo = $ruta_archivo;
    }
    public function setTamano_bytes($tamano_bytes)
    {
        $this->tamano_bytes = $tamano_bytes;
    }
    public function setError_msg($error_msg)
    {
        $this->error_msg = $error_msg;
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
        return "ExportJob {$this->id}: {$this->tipo} en formato {$this->formato} (Estado: {$this->estado})";
    }
}
