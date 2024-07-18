<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablaCatalagoFormatosCampo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catFormatos_campo', function (Blueprint $table) {

            $table->increments('ID_FORMATO');
            $table->text('NOMBRE');
            $table->text('DESCRIPCION');
            $table->text('RUTA_PDF');
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
