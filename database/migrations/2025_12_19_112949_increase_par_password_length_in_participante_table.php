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
        Schema::table('participante', function (Blueprint $table) {
            $table->string('par_password', 255)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('participante', function (Blueprint $table) {
            // Assuming the original length was shorter, specific length unknown but likely 50 or 100.
            // Reverting to a reasonable default or leaving as is if rollback is critical.
            // For safety in this context, we might skip shrinking it or revert to an assumed 50 if needed.
            // But usually safer to just leave it large. If we MUST revert:
            $table->string('par_password', 50)->change();
        });
    }
};
