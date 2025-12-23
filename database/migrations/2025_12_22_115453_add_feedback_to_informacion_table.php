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
            $table->tinyInteger('inf_valoracion')->nullable()->after('inf_nota'); // 1 to 5
            $table->boolean('inf_repetir')->nullable()->after('inf_valoracion'); // 1=Yes, 0=No
            $table->text('inf_mejora')->nullable()->after('inf_repetir');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('informacion', function (Blueprint $table) {
            $table->dropColumn(['inf_valoracion', 'inf_repetir', 'inf_mejora']);
        });
    }
};
