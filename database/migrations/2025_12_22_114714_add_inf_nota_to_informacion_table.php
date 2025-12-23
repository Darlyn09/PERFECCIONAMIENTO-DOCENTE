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
        Schema::table('informacion', function (Blueprint $table) {
            $table->decimal('inf_nota', 3, 1)->nullable()->after('inf_asistencia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('informacion', function (Blueprint $table) {
            $table->dropColumn('inf_nota');
        });
    }
};
