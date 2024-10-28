<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablaSeguimientotrabajadores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('seguimientotrabajadores', function (Blueprint $table) {
            $table->increments('ID_SEGUIMIENTOTRABAJADORES');
            $table->integer('proyecto_id')->nullable();

            $table->integer('TRABAJADOR_ID')->nullable();

            $table->text('TRABAJADOR_ESTADOCORREO')->nullable();
            $table->text('TRABAJADOR_ESTADOCONTESTADO')->nullable();
            $table->text('TRABAJADOR_ESTADOCARGADO')->nullable();

            $table->text('TRABAJADOR_FECHAINICIO')->nullable();
            $table->text('TRABAJADOR_FECHAFIN')->nullable();
            $table->text('TRABAJADOR_FECHAAPLICACION')->nullable();

            $table->text('TRABAJADOR_RUTAEVIDENCIA1')->nullable();
            $table->text('TRABAJADOR_RUTAEVIDENCIA2')->nullable();

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
