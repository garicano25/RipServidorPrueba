<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableHojaSeguridadSustanciaQuimica extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catHojasSeguridad_SustanciasQuimicas', function (Blueprint $table) {

            $table->increments('ID_HOJA_SUSTANCIA');
            $table->integer('HOJA_SEGURIDAD_ID');
            $table->integer('SUSTANCIA_QUIMICA_ID');
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
