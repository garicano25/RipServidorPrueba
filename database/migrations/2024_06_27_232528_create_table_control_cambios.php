<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableControlCambios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('controlCambios', function (Blueprint $table) {

            $table->increments('ID_CONTROL_CAMBIO');
            $table->integer('RECONOCIMIENTO_ID');
            $table->integer('REALIZADO_ID');
            $table->text('DESCRIPCION_REALIZADO');
            $table->integer('AUTORIZADO_ID')->nullable();
            $table->text('DESCRIPCION_AUTORIZADO')->nullable();
            $table->boolean('AUTORIZADO')->default(0);
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
