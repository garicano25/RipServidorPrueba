<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablaRecopsicotrabajadores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('recopsicotrabajadores', function (Blueprint $table) {
            $table->increments('ID_RECOPSICOTRABAJADOR');
            $table->integer('RECPSICO_ID')->nullable();
            $table->integer('RECPSICOTRABAJADOR_ORDEN')->nullable();
            $table->text('RECPSICOTRABAJADOR_NOMBRE')->nullable();
            $table->text('RECPSICOTRABAJADOR_GENERO')->nullable();
            $table->text('RECPSICOTRABAJADOR_AREA')->nullable();
            $table->text('RECPSICOTRABAJADOR_CATEGORIA')->nullable();
            $table->text('RECPSICOTRABAJADOR_FICHA')->nullable();
            $table->text('RECPSICOTRABAJADOR_CORREO')->nullable();
            $table->text('RECPSICOTRABAJADOR_SELECCIONADO')->nullable();
            $table->text('RECPSICOTRABAJADOR_OBSERVACION')->nullable();
            $table->text('RECPSICOTRABAJADOR_MODALIDAD')->nullable();
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
