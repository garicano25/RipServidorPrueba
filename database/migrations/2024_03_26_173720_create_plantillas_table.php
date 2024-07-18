<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlantillasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plantillas_imagenes_clientes', function (Blueprint $table) {
            $table->increments('ID_PLANTILLA_IMAGEN');
            $table->string('NOMBRE_PLANTILLA');
            $table->string('RUTA_IMAGEN'); 
            $table->text('DESCRIPCION_PLANTILLA');
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
        Schema::dropIfExists('plantillas_imagenes_clientes');
    }
}
