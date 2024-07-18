<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditarTablaClientepartidas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('clientepartidas', 'contratos_partidas');
        Schema::table('contratos_partidas', function (Blueprint $table) {
            $table->dropColumn('cliente_id');
        });

        #SE AGREGO LA FK DE CONTRATO_ID DE FORMA MANUAL
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
