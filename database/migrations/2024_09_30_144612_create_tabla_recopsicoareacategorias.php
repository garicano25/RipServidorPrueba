<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablaRecopsicoareacategorias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recopsicoareacategorias', function (Blueprint $table) {
            $table->increments('ID_RECOPSICOAREACATEGORIAS');
            $table->integer('RECPSICOAREA_ID')->nullable();
            $table->text('RECPSICOCATEGORIA_ID')->nullable();
            $table->text('RECPSICOAREACATEGORIAS_ACTIVIDAD')->nullable();
            $table->text('RECPSICOAREACATEGORIAS_GEH')->nullable();
            $table->text('RECPSICOAREACATEGORIAS_TOTAL')->nullable();
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
