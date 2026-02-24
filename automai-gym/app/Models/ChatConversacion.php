<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatConversacion extends Model
{
    protected $table = 'chat_conversaciones';

    protected $fillable = [
        'id_usuario',
        'titulo',
        'resumen',
    ];

    public function mensajes(): HasMany
    {
        return $this->hasMany(ChatMensajeIA::class, 'id_conversacion', 'id');
    }
}
