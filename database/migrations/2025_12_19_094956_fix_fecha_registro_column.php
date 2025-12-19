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
        // Redefinir columna para QUITAR el 'ON UPDATE CURRENT_TIMESTAMP'
        DB::statement("ALTER TABLE participante CHANGE fecha_registro fecha_registro TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir a comportamiento anterior (opcional, pero buena práctica)
        DB::statement("ALTER TABLE participante CHANGE fecha_registro fecha_registro TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
    }
};
