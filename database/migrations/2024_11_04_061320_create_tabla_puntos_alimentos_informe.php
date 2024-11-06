<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablaPuntosAlimentosInforme extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('puntosAlimentosInformes', function (Blueprint $table) {
            
            $table->increments('ID_PUNTO_ALIMENTOS');
            $table->integer('PROYECTO_ID')->nullable();

            $table->integer('PUNTO_MEDICION_ALIMENTOS')->nullable();
            $table->text('AREA_ALIMENTOS')->nullable();
            $table->text('UBICACION_ALIMENTOS')->nullable();

            $table->integer('COLIFORME_TOTALES_ALIMENTOS')->nullable();
            $table->date('FECHA_MEDICION_ALIMENTOS_TOTALES')->nullable();
            $table->text('UNIDAD_ALIMENTOS_TOTALES')->nullable();
            $table->text('METODO_ALIMENTOS_TOTALES')->nullable();
            $table->integer('TRABAJADORES_ALIMENTOS_TOTALES')->nullable();
            $table->text('CONCENTRACION_ALIMENTOS_TOTALES')->nullable();
            $table->text('CONCENTRACION_PERMISIBLE_TOTALES')->nullable(); 
            
            $table->integer('COLIFORME_FECALES_ALIMENTOS')->nullable();
            $table->date('FECHA_MEDICION_ALIMENTOS_FECALES')->nullable();
            $table->text('UNIDAD_ALIMENTOS_FECALES')->nullable();
            $table->text('METODO_ALIMENTOS_FECALES')->nullable();
            $table->integer('TRABAJADORES_ALIMENTOS_FECALES')->nullable();
            $table->text('CONCENTRACION_ALIMENTOS_FECALES')->nullable();
            $table->text('CONCENTRACION_PERMISIBLE_FECALES')->nullable();

            $table->text('PARAMETRO_COLOR')->nullable();
            $table->text('UNIDAD_COLOR')->nullable();
            $table->text('METODO_COLOR')->nullable();
            $table->text('CONCENTRACION_COLOR')->nullable(); 
            
            $table->text('PARAMETRO_OLOR')->nullable();
            $table->text('UNIDAD_OLOR')->nullable();
            $table->text('METODO_OLOR')->nullable();
            $table->text('CONCENTRACION_OLOR')->nullable();   
            
            $table->text('PARAMETRO_SABOR')->nullable();
            $table->text('UNIDAD_SABOR')->nullable();
            $table->text('METODO_SABOR')->nullable();
            $table->text('CONCENTRACION_SABOR')->nullable();





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
