<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableServiciosProyecto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('serviciosProyecto', function (Blueprint $table) {
            $table->increments('ID_SERVICIO_PROYECTO');
            $table->integer('PROYECTO_ID');
            $table->boolean('HI')->default(0);
            $table->boolean('HI_PROGRAMA')->default(0);
            $table->boolean('HI_RECONOCIMIENTO')->default(0);
            $table->boolean('HI_EJECUCION')->default(0);
            $table->boolean('HI_INFORME')->default(0);
            

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
