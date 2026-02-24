<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_mensajes_ia', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_conversacion');
            $table->unsignedInteger('id_usuario');
            $table->enum('rol', ['user', 'assistant', 'tool']);
            $table->longText('contenido');
            $table->string('tool_name')->nullable();
            $table->json('tool_payload')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['id_conversacion', 'id']);
            $table->index('id_usuario');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_mensajes_ia');
    }
};
