<?php

/**
 * Clase base para los modelos de GymBot.
 * Define propiedades comunes como id y timestamps, junto con
 * constructor, getters, setters y __toString().
 */
class BaseModel
{
    /**
     * Identificador de la entidad
     * @var mixed
     */
    protected $id;

    /**
     * Fecha de creación (opcional)
     * @var mixed
     */
    protected $creado_en;

    /**
     * Fecha de última actualización (opcional)
     * @var mixed
     */
    protected $actualizado_en;

    /**
     * Constructor
     *
     * @param mixed $id
     * @param mixed $creado_en
     * @param mixed $actualizado_en
     */
    public function __construct($id, $creado_en = null, $actualizado_en = null)
    {
        $this->id = $id;
        $this->creado_en = $creado_en;
        $this->actualizado_en = $actualizado_en;
    }

    // Getters
    public function getId()
    {
        return $this->id;
    }
    public function getCreado_en()
    {
        return $this->creado_en;
    }
    public function getActualizado_en()
    {
        return $this->actualizado_en;
    }

    // Setters
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setCreado_en($creado_en)
    {
        $this->creado_en = $creado_en;
    }
    public function setActualizado_en($actualizado_en)
    {
        $this->actualizado_en = $actualizado_en;
    }

    // Representación en string
    public function __toString()
    {
        $parts = [];
        $parts[] = "ID: {$this->id}";
        if ($this->creado_en) {
            $parts[] = "creado en: {$this->creado_en}";
        }
        if ($this->actualizado_en) {
            $parts[] = "actualizado en: {$this->actualizado_en}";
        }
        return implode(' | ', $parts);
    }
}
