<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditarTablaContratosPartidas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contratos_partidas', function (Blueprint $table) {
            // $table->int('CONVENIO_ID')->nullable(); la fk de CONVENIO_ID se agrego de manera manual
            $table->text('UNIDAD_MEDIDA')->nullable();
            $table->float('PRECIO_UNITARIO')->nullable();
            $table->float('DESCUENTO')->nullable();
            $table->boolean('ACTIVO')->default(1);


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
