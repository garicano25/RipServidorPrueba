<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaDocumentosEquipos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipos_documentos', function (Blueprint $table) {
           

            $table->increments('ID_EQUIPO_DOCUMENTO');
            // $table->integer('EQUIPO_ID')->unsigned(); SE AGREGO LA FK DE EQUIPO_ID DE FORMA MANUAL

            $table->text('NOMBRE_DOCUMENTO');
            $table->text('RUTA_DOCUMENTO');
            $table->text('DOCUMENTO_TIPO');
            $table->date('FECHA_CARGA');
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
