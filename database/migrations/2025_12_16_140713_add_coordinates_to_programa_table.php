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
        Schema::table('programa', function (Blueprint $table) {
            $table->decimal('pro_lat', 10, 8)->nullable()->after('pro_hora_termino');
            $table->decimal('pro_lng', 11, 8)->nullable()->after('pro_lat');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('programa', function (Blueprint $table) {
            $table->dropColumn(['pro_lat', 'pro_lng']);
        });
    }
};
