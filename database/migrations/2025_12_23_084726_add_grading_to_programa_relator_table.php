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
        Schema::table('programa_relator', function (Blueprint $table) {
            $table->decimal('rr_nota', 3, 1)->nullable()->after('rel_login');
            $table->integer('rr_asistencia')->nullable()->after('rr_nota');
            $table->timestamp('rr_certificado')->nullable()->after('rr_asistencia');
            $table->text('rr_observaciones')->nullable()->after('rr_certificado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('programa_relator', function (Blueprint $table) {
            $table->dropColumn(['rr_nota', 'rr_asistencia', 'rr_certificado', 'rr_observaciones']);
        });
    }
};
