<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCentroInformacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('centroInformacion', function (Blueprint $table) {

            $table->increments('ID_CENTRO_INFORMACION');
            $table->text('TITULO');
            $table->text('DESCRIPCION')->nullable();
            $table->text('RUTA_DOCUMENTO')->nullable();
            $table->text('RUTA_LINK')->nullable();
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
