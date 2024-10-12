<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablaRecopsicoproyectotrabajadores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recopsicoproyectotrabajadores', function (Blueprint $table) {
            $table->increments('ID_RECOPSICOPROYECTOTRABAJADORES');
            $table->integer('RECPSICOTRABAJADOR_ID')->nullable();
            $table->integer('RECPSICO_ID')->nullable();
            $table->text('RECPSICOTRABAJADOR_ESTADOCORREO')->nullable();
            $table->text('RECPSICOTRABAJADOR_ESTADOCONTESTADO')->nullable();
            $table->text('RECPSICOTRABAJADOR_ESTADOCARGADO')->nullable();
            $table->text('RECPSICOTRABAJADOR_')->nullable();
            $table->text('RECPSICOTRABAJADOR_')->nullable();
            $table->text('RECPSICOTRABAJADOR_')->nullable();
            $table->text('RECPSICOTRABAJADOR_')->nullable();
            $table->text('RECPSICOTRABAJADOR_')->nullable();
            $table->text('RECPSICOTRABAJADOR_TIPOJORNADA')->nullable();
            $table->text('RECPSICOTRABAJADOR_ROTACIONTURNOS')->nullable();
            $table->text('RECPSICOTRABAJADOR_TIEMPOPUESTO')->nullable();
            $table->text('RECPSICOTRABAJADOR_TIEMPOEXPERIENCIA')->nullable();
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
