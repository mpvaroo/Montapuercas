<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistroProgreso extends Model
{
    use HasFactory;

    protected $table = 'registros_progreso';
    protected $primaryKey = 'id_registro_progreso';
    public $timestamps = false; // fecha_registro used instead

    protected $fillable = [
        'id_usuario',
        'fecha_registro',
        'peso_kg_registro',
        'cintura_cm_registro',
        'pecho_cm_registro',
        'cadera_cm_registro',
        'notas_progreso',
    ];

    protected $casts = [
        'fecha_registro' => 'date',
        'peso_kg_registro' => 'decimal:2',
        'cintura_cm_registro' => 'decimal:2',
        'pecho_cm_registro' => 'decimal:2',
        'cadera_cm_registro' => 'decimal:2',
    ];

    /**
     * Get the user that owns the progress record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }
}
