<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('curso', function (Blueprint $table) {
            if (!Schema::hasColumn('curso', 'cur_cupos')) {
                $table->integer('cur_cupos')->nullable()->after('cur_horas')->comment('Cupos máximos del curso');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('curso', function (Blueprint $table) {
            if (Schema::hasColumn('curso', 'cur_cupos')) {
                $table->dropColumn('cur_cupos');
            }
        });
    }
};
