<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Clave foránea para chat_conversaciones
        Schema::table('chat_conversaciones', function (Blueprint $table) {
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->onDelete('cascade');
        });

        // 2. Claves foráneas para chat_mensajes_ia
        Schema::table('chat_mensajes_ia', function (Blueprint $table) {
            // Relación con la conversación
            $table->foreign('id_conversacion')->references('id')->on('chat_conversaciones')->onDelete('cascade');
            // Relación con el usuario
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_mensajes_ia', function (Blueprint $table) {
            $table->dropForeign(['id_conversacion']);
            $table->dropForeign(['id_usuario']);
        });

        Schema::table('chat_conversaciones', function (Blueprint $table) {
            $table->dropForeign(['id_usuario']);
        });
    }
};
