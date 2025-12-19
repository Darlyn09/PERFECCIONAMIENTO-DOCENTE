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
        Schema::create('programa_relator', function (Blueprint $table) {
            $table->id();
            $table->integer('pro_id'); // Signed integer (Legacy compatibility)
            $table->string('rel_login', 255);
            $table->timestamps();

            // Foreign Keys
            $table->foreign('pro_id')->references('pro_id')->on('programa')->onDelete('cascade');
            $table->foreign('rel_login')->references('rel_login')->on('relator')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programa_relator');
    }
};
