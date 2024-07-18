<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearRecsensorialTablaClientesProporcionado extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recsensorial_tablaClientes_proporcionado', function (Blueprint $table) {
            $table->increments('ID_TABLA_CLIENTE_PROPORCIONADO');
            $table->integer('RECONOCIMIENTO_ID');
            $table->text('AREA_PROPORCIONADACLIENTE')->nullable();
            $table->text('CATEGORIA_PROPORCIONADACLIENTE')->nullable();
            $table->text('PRODUCTO_PROPORCIONADACLIENTE')->nullable();
            $table->integer('SUSTANCIA_PROPORCIONADACLIENTE');
            $table->text('PPT_PROPORCIONADACLIENTE')->nullable();
            $table->text('CT_PROPORCIONADACLIENTE')->nullable();
            $table->text('PUNTOS_PROPORCIONADACLIENTE')->nullable();
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
