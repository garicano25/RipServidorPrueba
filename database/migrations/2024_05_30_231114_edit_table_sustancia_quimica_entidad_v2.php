<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditTableSustanciaQuimicaEntidadV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sustanciaQuimicaEntidad', function (Blueprint $table) {
           
            $table->dropColumn('ESTADO_FISICO');
            $table->dropColumn('VOLATILIDAD');
            $table->dropColumn('TEM_EBULLICION');
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
