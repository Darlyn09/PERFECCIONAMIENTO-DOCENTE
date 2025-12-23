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
        Schema::table('programa', function (Blueprint $table) {
            $table->boolean('pro_estado')->default(1)->after('pro_hora_termino');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('programa', function (Blueprint $table) {
            $table->dropColumn('pro_estado');
        });
    }
};
