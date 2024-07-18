<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaContratosDocumentosCierre extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contratos_documentos_cierre', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            $table->increments('ID_DOCUMENTO_CIERRE');
            $table->text('NOMBRE');
            $table->text('RUTA_DOCUMENTO');
            $table->boolean('ELIMINADO')->default(0);
            $table->timestamps();

            ///lA FK DE CONTRATO_ID SE LE AGREGO DE MANERA MANUAL

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
