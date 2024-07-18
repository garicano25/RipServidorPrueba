<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRecsensorialTablaInforme extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recsensorial_tabla_informes', function (Blueprint $table) {

            $table->increments('ID_TABLA_INFORME');
            $table->integer('RECONOCIMIENTO_ID');
            $table->integer('CATEGORIA_ID');
            $table->integer('PRODUCTO_ID');
            $table->integer('SUSTANCIA_ID');
            $table->integer('PPT');
            $table->integer('CT');
            $table->integer('PUNTOS');
            $table->text('JUSTIFICACION');
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
