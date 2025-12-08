<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentosEpp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentos_epps', function (Blueprint $table) {
            $table->increments('ID_EPP_DOCUMENTO');
            $table->integer('EPP_ID')->nullable();
            $table->text('DOCUMENTO_TIPO')->nullable();
            $table->text('NOMBRE_DOCUMENTO')->nullable();
            $table->text('FOTO_DOCUMENTO')->nullable();
            $table->text('EPP_PDF')->nullable();
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
