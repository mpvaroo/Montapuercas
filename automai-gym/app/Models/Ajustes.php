<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ajustes extends Model
{
    use HasFactory;
    protected $table = 'ajustes_usuario';
    protected $primaryKey = 'id_ajustes_usuario';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'notificaciones_entrenamiento_activas',
        'notificaciones_clases_activas',
        'tono_ia_coach',
        'idioma_preferido',
        'semana_empieza_en',
    ];

    protected $casts = [
        'notificaciones_entrenamiento_activas' => 'boolean',
        'notificaciones_clases_activas' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }
}
