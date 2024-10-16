<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablaCatalogoguiaspsico extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalogoguiaspsico', function (Blueprint $table) {
            $table->increments('ID_GUIAPREGUNTA');

            $table->integer('TIPOGUIA')->nullable();

            $table->text('PREGUNTA_ID')->nullable();
            $table->text('PREGUNTA_NOMBRE')->nullable();
            $table->text('PREGUNTA_EXPLICACION')->nullable();

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
