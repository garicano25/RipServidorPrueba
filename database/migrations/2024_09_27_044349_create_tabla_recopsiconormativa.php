<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablaRecopsiconormativa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recopsiconormativa', function (Blueprint $table) {
            $table->increments('ID_RECOPSICONORMATIVA');
            $table->integer('RECPSICO_ID')->nullable();
            $table->boolean('RECPSICO_GUIAI')->nullable();
            $table->boolean('RECPSICO_GUIAII')->nullable();
            $table->boolean('RECPSICO_GUIAIII')->nullable();
            $table->integer('RECPSICO_TOTALTRABAJADORES')->nullable();
            $table->text('RECPSICO_TIPOAPLICACION')->nullable();
            $table->integer('RECPSICO_TOTALAPLICACION')->nullable();
            $table->boolean('RECPSICO_GENEROS')->nullable();
            $table->integer('RECPSICO_PORCENTAJEHOMBRESTRABAJO')->nullable();
            $table->integer('RECPSICO_PORCENTAJEMUJERESTRABAJO')->nullable();
            $table->integer('RECPSICO_TOTALHOMBRESTRABAJO')->nullable();
            $table->integer('RECPSICO_TOTALMUJERESTRABAJO')->nullable();
            $table->integer('RECPSICO_TOTALHOMBRESSELECCION')->nullable();
            $table->integer('RECPSICO_TOTALMUJERESSELECCION')->nullable();
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
