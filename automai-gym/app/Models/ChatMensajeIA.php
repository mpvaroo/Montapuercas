<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMensajeIA extends Model
{
    protected $table = 'chat_mensajes_ia';

    public $timestamps = false; // solo tiene created_at via useCurrent()

    protected $fillable = [
        'id_conversacion',
        'id_usuario',
        'rol',
        'contenido',
        'tool_name',
        'tool_payload',
    ];

    protected $casts = [
        'tool_payload' => 'array',
    ];

    public function conversacion(): BelongsTo
    {
        return $this->belongsTo(ChatConversacion::class, 'id_conversacion', 'id');
    }
}
