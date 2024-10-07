<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSustanciasEntidadBeis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sustanciasEntidadBeis', function (Blueprint $table) {

            $table->increments('ID_BEI');
            $table->integer('SUSTANCIA_QUIMICA_ID');
            $table->integer('ENTIDAD_ID');
            $table->text('DETERMINANTE');
            $table->text('TIEMPO_MUESTREO');
            $table->text('BEI_DESCRIPCION');
            $table->text('NOTACION');
            $table->text('RECOMENDACION')->nullable();
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
