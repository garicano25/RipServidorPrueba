<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablaProyectotrabajadores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proyectotrabajadores', function (Blueprint $table) {
            $table->increments('ID_PROYECTOTRABAJADORES');
            $table->integer('proyecto_id')->nullable();

            $table->integer('TRABAJADOR_ID')->nullable();

            $table->text('TRABAJADOR_MODALIDAD')->nullable();
            $table->text('TRABAJADOR_OBSERVACION')->nullable();
            $table->text('TRABAJADOR_SELECCIONADO')->nullable();

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
