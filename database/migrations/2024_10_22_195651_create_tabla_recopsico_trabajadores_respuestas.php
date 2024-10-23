<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablaRecopsicoTrabajadoresRespuestas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recopsicoTrabajadoresRespuestas', function (Blueprint $table) {
            $table->increments('ID_RECOPSICORESPUESTAS');

            $table->integer('RECPSICO_ID')->nullable();
            $table->integer('RECPSICO_TRABAJADOR')->nullable();
            
            $table->text('RECPSICO_GUIAI_RESPUESTAS')->nullable();
            $table->text('RECPSICO_GUIAII_RESPUESTAS')->nullable();
            $table->text('RECPSICO_GUIAIII_RESPUESTAS')->nullable();
            $table->integer('RECPSICO_GUIAV_ID')->nullable();
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
