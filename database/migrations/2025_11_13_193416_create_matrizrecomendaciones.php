<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatrizrecomendaciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matrizrecomendaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('proyecto_id');
            $table->unsignedBigInteger('reporteregistro_id')->nullable();
            $table->unsignedBigInteger('area_id');
            $table->unsignedBigInteger('categoria_id');
            $table->unsignedBigInteger('agente_id')->default(15);
            $table->json('recomendaciones_json')->nullable();
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
