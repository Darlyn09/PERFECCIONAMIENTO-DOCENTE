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
        Schema::create('certificados', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            // 'tipo' indicates what this certificate is for:
            // 'defecto' -> global default
            // 'curso' -> specific to a course
            // 'evento' -> specific to an event
            $table->enum('tipo', ['defecto', 'curso', 'evento'])->default('defecto');

            // 'referencia_id' holds the ID of the course or event. Null if type is 'defecto'.
            $table->unsignedBigInteger('referencia_id')->nullable();

            // JSON column to store all design settings (texts, fonts, margins, colors)
            $table->json('configuracion')->nullable();

            // Paths to images
            $table->string('imagen_fondo')->nullable();
            $table->string('firma_imagen')->nullable();

            // Physical properties
            $table->integer('width')->default(800);
            $table->integer('height')->default(600);
            $table->string('page_size')->default('custom'); // custom, letter, a4
            $table->string('orientation')->default('landscape'); // landscape, portrait

            $table->timestamps();

            // Indexes for faster lookup
            $table->index(['tipo', 'referencia_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificados');
    }
};
