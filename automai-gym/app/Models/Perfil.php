<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Perfil extends Model
{
    use HasFactory;
    protected $table = 'perfiles_usuario';
    protected $primaryKey = 'id_perfil_usuario';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'nombre_real_usuario',
        'telefono_usuario',
        'ruta_foto_perfil_usuario',
        'objetivo_principal_usuario',
        'dias_entrenamiento_semana_usuario',
        'nivel_usuario',
        'peso_kg_usuario',
        'altura_cm_usuario',
        'fecha_inicio_usuario',
    ];

    protected $casts = [
        'fecha_inicio_usuario' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Get the URL for the user's avatar.
     * Paths are stored as 'avatars/filename.ext' relative to public/.
     * Returns empty string if no avatar is set (view handles fallback with initials).
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->ruta_foto_perfil_usuario) {
            return asset($this->ruta_foto_perfil_usuario);
        }

        return '';
    }
}
