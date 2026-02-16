<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Seguridad extends Model
{
    use HasFactory;
    protected $table = 'seguridad_usuario';
    protected $primaryKey = 'id_seguridad_usuario';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'requiere_cambio_contrasena',
        'fecha_ultimo_cambio_contrasena',
        'intentos_fallidos_login',
        'fecha_ultimo_intento_fallido',
    ];

    protected $casts = [
        'requiere_cambio_contrasena' => 'boolean',
        'fecha_ultimo_cambio_contrasena' => 'datetime',
        'fecha_ultimo_intento_fallido' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }
}
