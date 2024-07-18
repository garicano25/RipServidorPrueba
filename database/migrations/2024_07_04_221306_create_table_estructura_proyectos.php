<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableEstructuraProyectos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estructuraProyectos', function (Blueprint $table) {
            $table->increments('ID_ESTRUCTURA_PROYECTO');
            $table->integer('PROYECTO_ID');
            $table->integer('ETIQUETA_ID');
            $table->integer('OPCION_ID');
            $table->timestamps();
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
