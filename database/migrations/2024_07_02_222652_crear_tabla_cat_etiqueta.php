<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaCatEtiqueta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cat_etiquetas', function (Blueprint $table) {

            $table->increments('ID_ETIQUETA');
            $table->text('NOMBRE_ETIQUETA')->nullable();
            $table->text('DESCRIPCION_ETIQUETA')->nullable();
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
