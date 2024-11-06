<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablaPuntosSuperficiesInertesInforme extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('puntosSuperficiesInertesInformes', function (Blueprint $table) {

            $table->increments('ID_PUNTO_INERTES');
            $table->integer('PROYECTO_ID')->nullable();

            $table->integer('PUNTO_MEDICION_INERTES')->nullable();
            $table->text('AREA_INERTES')->nullable();
            $table->text('UBICACION_INERTES')->nullable();

            $table->integer('COLIFORME_TOTALES_INERTES')->nullable();
            $table->date('FECHA_MEDICION_INERTES_TOTALES')->nullable();
            $table->text('UNIDAD_INERTES_TOTALES')->nullable();
            $table->text('METODO_INERTES_TOTALES')->nullable();
            $table->integer('TRABAJADORES_INERTES_TOTALES')->nullable();
            $table->text('CONCENTRACION_INERTES_TOTALES')->nullable();
            $table->text('CONCENTRACION_PERMISIBLE_TOTALES')->nullable();

            $table->integer('COLIFORME_FECALES_INERTES')->nullable();
            $table->date('FECHA_MEDICION_INERTES_FECALES')->nullable();
            $table->text('UNIDAD_INERTES_FECALES')->nullable();
            $table->text('METODO_INERTES_FECALES')->nullable();
            $table->integer('TRABAJADORES_INERTES_FECALES')->nullable();
            $table->text('CONCENTRACION_INERTES_FECALES')->nullable();
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
