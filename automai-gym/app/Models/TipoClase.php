<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoClase extends Model
{
    use HasFactory;

    protected $table = 'tipos_clase';
    protected $primaryKey = 'id_tipo_clase';
    public $timestamps = false;

    protected $fillable = [
        'nombre_tipo_clase',
        'descripcion_tipo_clase',
    ];

    /**
     * Get the classes for the type.
     */
    public function clases(): HasMany
    {
        return $this->hasMany(ClaseGimnasio::class, 'id_tipo_clase', 'id_tipo_clase');
    }
}
