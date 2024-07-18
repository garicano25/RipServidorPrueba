<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaCatetiquetasOpciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('catetiquetas_opciones', function (Blueprint $table) {
        $table->increments('ID_OPCIONES_ETIQUETAS');
        $table->integer('ETIQUETA_ID');
        $table->text('NOMBRE_OPCIONES')->nullable();
        $table->boolean('ACTIVO')->default(1);
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
