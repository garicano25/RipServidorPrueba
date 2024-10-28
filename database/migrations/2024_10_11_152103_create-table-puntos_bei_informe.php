<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePuntosBeiInforme extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('puntosBeiInforme', function (Blueprint $table) {

            $table->increments('ID_BEI_INFORME');
            $table->integer('NUM_PUNTO_BEI');
            $table->integer('BEI_ID');
            $table->integer('AREA_ID');
            $table->integer('CATEGORIA_ID');
            $table->text('NOMBRE_BEI');
            $table->text('GENERO_BEI');
            $table->text('FICHA_BEI');
            $table->text('EDAD_BEI');
            $table->text('ANTIGUEDAD_BEI');
            $table->text('MUESTRA_BEI');
            $table->text('UNIDAD_MEDIDA_BEI');
            $table->text('RESULTADO_BEI');
            $table->text('REFERENCIA_BEI');
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
