<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablaFotosRecopsico extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recopsicoFotosTrabajadores', function (Blueprint $table) {
            $table->increments('ID_RECOPSICOFOTOTRABAJADOR');

            $table->integer('RECPSICO_ID')->nullable();
            $table->integer('RECPSICO_TRABAJADOR')->nullable();
            
            $table->text('RECPSICO_FOTOPREGUIA')->nullable();
            $table->text('RECPSICO_FOTOPOSTGUIA')->nullable();
            $table->text('RECPSICO_FOTOPRESENCIAL')->nullable();
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
