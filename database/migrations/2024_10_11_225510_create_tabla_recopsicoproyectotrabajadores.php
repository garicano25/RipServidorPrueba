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
            $table->integer('RECPSICO_ID')->nullable();

            $table->integer('RECPSICOTRABAJADOR_ID')->nullable();

            $table->text('RECPSICOTRABAJADOR_MODALIDAD')->nullable();
            $table->text('RECPSICOTRABAJADOR_OBSERVACION')->nullable();
            $table->text('RECPSICOTRABAJADOR_SELECCIONADO')->nullable();

            $table->text('RECPSICOTRABAJADOR_ESTADOCORREO')->nullable();
            $table->text('RECPSICOTRABAJADOR_ESTADOCONTESTADO')->nullable();
            $table->text('RECPSICOTRABAJADOR_ESTADOCARGADO')->nullable();

            $table->text('RECPSICOTRABAJADOR_FECHAINICIO')->nullable();
            $table->text('RECPSICOTRABAJADOR_FECHAFIN')->nullable();
            $table->text('RECPSICOTRABAJADOR_FECHAAPLICACION')->nullable();

            $table->text('RECPSICOTRABAJADOR_RUTAEVIDENCIA1')->nullable();
            $table->text('RECPSICOTRABAJADOR_RUTAEVIDENCIA2')->nullable();

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
