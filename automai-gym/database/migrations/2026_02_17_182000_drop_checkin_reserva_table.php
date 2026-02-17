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
        Schema::dropIfExists('checkin_reserva');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('checkin_reserva', function (Blueprint $table) {
            $table->increments('id_checkin_reserva');
            $table->unsignedInteger('id_reserva_clase')->unique();
            $table->boolean('realizado_checkin')->default(false);
            $table->dateTime('fecha_checkin')->nullable();
            $table->enum('metodo_checkin', ['manual', 'qr', 'recepcion'])->default('manual');

            $table->foreign('id_reserva_clase')->references('id_reserva_clase')->on('reservas_clase')->onDelete('cascade')->onUpdate('cascade');
        });
    }
};
