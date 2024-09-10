<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TablaCatProteccion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catProteccion_auditiva', function (Blueprint $table) {
            $table->increments('ID_PROTECCION');
            $table->text('TIPO');
            $table->text('MODELO');
            $table->text('MARCA');
            $table->text('NRR');
            $table->text('SNR')->nullable();
            $table->text('CUMPLIMIENTO');
            $table->text('H');
            $table->text('M');
            $table->text('L');
            $table->text('ATENUACIONES_JSON');
            $table->text('DESVIACIONES_JSON');
            $table->text('RUTA_PDF');
            $table->text('RUTA_IMG');
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
