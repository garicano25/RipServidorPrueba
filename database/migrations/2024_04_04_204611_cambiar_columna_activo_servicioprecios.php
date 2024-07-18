<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CambiarColumnaActivoServicioprecios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('servicioprecios', function (Blueprint $table) {
            $table->dropColumn('ACTIVO_PARTIDAPROVEEDOR');
        });

        Schema::table('servicioprecios', function (Blueprint $table) {
            $table->boolean('ACTIVO_PARTIDAPROVEEDOR')->nullable();
        });
    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
