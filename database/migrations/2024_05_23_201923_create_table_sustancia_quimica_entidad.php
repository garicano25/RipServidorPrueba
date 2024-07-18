<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSustanciaQuimicaEntidad extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sustanciaQuimicaEntidad', function (Blueprint $table) {

            $table->increments('ID_SUSTANCIA_QUIMICA_ENTIDAD');
            $table->integer('SUSTANCIA_QUIMICA_ID');
            $table->text('CONNOTACION');
            $table->text('VLE_PPT');
            $table->text('VLE_CT_P');
            $table->text('ENTIDAD');
            $table->text('DESCRIPCION_NORMATIVA');
            $table->text('DESCRIPCION_ENTIDAD');
            $table->text('JSON_BEIS');
            $table->text('VOLATILIDAD');
            $table->text('ESTADO_FISICO');
            $table->text('TEM_EBULLICION');
            $table->text('TIENE_BEIS');
            $table->boolean('ACTIVO')->default(1);
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
