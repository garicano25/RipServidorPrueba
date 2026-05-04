<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatergoDefiniciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catergo_definiciones', function (Blueprint $table) {
            $table->increments('ID_DEFINICIONES');
            $table->text('CONCEPTO_DEFINICION')->nullable();
            $table->text('DESCRIPCION_DEFINICION')->nullable();
            $table->text('FUENTE_DEFINICION')->nullable();
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
