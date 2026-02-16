<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClaseGimnasio extends Model
{
    use HasFactory;

    protected $table = 'clases_gimnasio';
    protected $primaryKey = 'id_clase_gimnasio';
    public $timestamps = false; // fecha_creacion_clase used instead

    protected $fillable = [
        'id_tipo_clase',
        'titulo_clase',
        'descripcion_clase',
        'instructor_clase',
        'fecha_inicio_clase',
        'fecha_fin_clase',
        'cupo_maximo_clase',
        'estado_clase',
    ];

    protected $casts = [
        'fecha_inicio_clase' => 'datetime',
        'fecha_fin_clase' => 'datetime',
        'fecha_creacion_clase' => 'datetime',
    ];

    /**
     * Get the type that owns the class.
     */
    public function tipoClase(): BelongsTo
    {
        return $this->belongsTo(TipoClase::class, 'id_tipo_clase', 'id_tipo_clase');
    }

    /**
     * Get the reservations for the class.
     */
    public function reservas(): HasMany
    {
        return $this->hasMany(ReservaClase::class, 'id_clase_gimnasio', 'id_clase_gimnasio');
    }
}
