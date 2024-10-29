<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablaGuiavNormativa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guiavNormativa', function (Blueprint $table) {
            $table->increments('ID_GUIAV');

            $table->integer('RECPSICO_ID')->nullable();

            $table->tinyInteger('RECPSICO_PREGUNTA1')->nullable();
            $table->tinyInteger('RECPSICO_PREGUNTA2')->nullable();
            $table->tinyInteger('RECPSICO_PREGUNTA3')->nullable();
            $table->tinyInteger('RECPSICO_PREGUNTA4')->nullable();
            $table->tinyInteger('RECPSICO_PREGUNTA5')->nullable();
            $table->tinyInteger('RECPSICO_PREGUNTA6')->nullable();
            $table->tinyInteger('RECPSICO_PREGUNTA7')->nullable();
            $table->tinyInteger('RECPSICO_PREGUNTA8')->nullable();
            $table->tinyInteger('RECPSICO_PREGUNTA9')->nullable();
            $table->tinyInteger('RECPSICO_PREGUNTA10')->nullable();
            $table->tinyInteger('RECPSICO_PREGUNTA11')->nullable();
            $table->tinyInteger('RECPSICO_PREGUNTA12')->nullable();
            $table->tinyInteger('RECPSICO_PREGUNTA13')->nullable();

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
