<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify the enum column to include 'admin' and 'recepcion'
        // Using raw SQL is often safer for ENUMs to avoid doctrine issues
        DB::statement("ALTER TABLE reservas_clase MODIFY COLUMN origen_reserva ENUM('usuario', 'ia_coach', 'admin', 'recepcion') DEFAULT 'usuario'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum
        DB::statement("ALTER TABLE reservas_clase MODIFY COLUMN origen_reserva ENUM('usuario', 'ia_coach') DEFAULT 'usuario'");
    }
};
