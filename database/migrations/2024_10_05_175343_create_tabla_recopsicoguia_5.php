<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablaRecopsicoguia5 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('recopsicoguia_5', function (Blueprint $table) {
            $table->increments('ID_RECOPSICOGUIA_5');
            $table->integer('RECPSICOTRABAJADOR_ID')->nullable();
            $table->integer('RECPSICO_ID')->nullable();
            $table->integer('RECPSICOTRABAJADOR_SEXO')->nullable();
            $table->integer('RECPSICOTRABAJADOR_EDAD')->nullable();
            $table->text('RECPSICOTRABAJADOR_FNACIMIENTO')->nullable();
            $table->text('RECPSICOTRABAJADOR_ESTADOCIVIL')->nullable();
            $table->text('RECPSICOTRABAJADOR_ESTUDIOS')->nullable();
            $table->text('RECPSICOTRABAJADOR_TIPOPUESTO')->nullable();
            $table->text('RECPSICOTRABAJADOR_TIPOCONTRATACION')->nullable();
            $table->text('RECPSICOTRABAJADOR_TIPOPERSONAL')->nullable();
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
