<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        \Illuminate\Support\Facades\DB::statement("SET SESSION sql_mode = ''");
        Schema::table('inscripcion', function (Blueprint $table) {
            $table->tinyInteger('ins_evaluacion')->nullable()->comment('Evaluación del curso (1-5 estrellas)');
            $table->boolean('ins_repetiria')->nullable()->comment('¿Repetiría el curso? 1=Sí, 0=No');
            $table->timestamp('ins_fecha_evaluacion')->nullable();
        });
    }

    public function down()
    {
        Schema::table('inscripcion', function (Blueprint $table) {
            $table->dropColumn(['ins_evaluacion', 'ins_repetiria', 'ins_fecha_evaluacion']);
        });
    }
};
