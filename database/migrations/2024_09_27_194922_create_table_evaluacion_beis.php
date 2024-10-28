<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableEvaluacionBeis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluacionBeis', function (Blueprint $table) {

            $table->increments('ID_RECSENSORIAL_BEI');
            $table->integer('SUSTANCIA_QUIMICA_ID');
            $table->integer('AREA_ID');
            $table->integer('CATEGORIA_ID');
            $table->integer('DETERMINANTE_ID');
            $table->text('NOMBRE_PERSONA');
            $table->date('FECHA_NACIMIENTO');
            $table->integer('EDAD');
            $table->text('ANTIGUEDAD_LABORAL');
            $table->text('TIEMPO_MUESTREO');
            $table->integer('NUMERO_MUESTRA');
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
