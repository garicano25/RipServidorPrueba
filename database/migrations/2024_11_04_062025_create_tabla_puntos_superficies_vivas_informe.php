<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablaPuntosSuperficiesVivasInforme extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('puntosSuperficiesVivasInformes', function (Blueprint $table) {

            $table->increments('ID_PUNTO_VIVAS');
            $table->integer('PROYECTO_ID')->nullable();

            $table->integer('PUNTO_MEDICION_VIVAS')->nullable();
            $table->text('AREA_VIVAS')->nullable();
            $table->text('UBICACION_VIVAS')->nullable();

            $table->integer('COLIFORME_TOTALES_VIVAS')->nullable();
            $table->date('FECHA_MEDICION_VIVAS_TOTALES')->nullable();
            $table->text('UNIDAD_VIVAS_TOTALES')->nullable();
            $table->text('METODO_VIVAS_TOTALES')->nullable();
            $table->integer('TRABAJADORES_VIVAS_TOTALES')->nullable();
            $table->text('CONCENTRACION_VIVAS_TOTALES')->nullable();
            $table->text('CONCENTRACION_PERMISIBLE_TOTALES')->nullable();

            $table->integer('COLIFORME_FECALES_VIVAS')->nullable();
            $table->date('FECHA_MEDICION_VIVAS_FECALES')->nullable();
            $table->text('UNIDAD_VIVAS_FECALES')->nullable();
            $table->text('METODO_VIVAS_FECALES')->nullable();
            $table->integer('TRABAJADORES_VIVAS_FECALES')->nullable();
            $table->text('CONCENTRACION_VIVAS_FECALES')->nullable();
            $table->text('CONCENTRACION_PERMISIBLE_FECALES')->nullable();

    
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
