<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablaPsicocatDefiniciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('psicocat_definiciones', function (Blueprint $table) {
            $table->increments('ID_DEFINICION_INFORME');
            $table->text('CONCEPTO')->nullable();
            $table->text('DESCRIPCION')->nullable();
            $table->text('FUENTE')->nullable();
            $table->boolean('ACTIVO')->nullable();
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
