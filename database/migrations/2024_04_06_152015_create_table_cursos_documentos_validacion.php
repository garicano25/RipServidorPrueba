<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCursosDocumentosValidacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cursos_documentos_validacion', function (Blueprint $table) {


            $table->increments('ID_DOCUMENTO_CURSO');
            // $table->integer('CURSO_ID')->unsigned(); SE AGREGO LA FK DE CURS_ID DE FORMA MANUAL
           
            $table->text('NOMBRE_DOCUMENTO')->nullable();
            $table->text('RUTA_PDF')->nullable();
            $table->boolean('ELIMINADO')->default(0);
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
