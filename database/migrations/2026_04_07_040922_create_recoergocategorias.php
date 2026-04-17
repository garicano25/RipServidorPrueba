<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecoergocategorias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recoergocategorias', function (Blueprint $table) {

            $table->increments('ID_CATEGORIA_ERGO');
            $table->text('RECO_ID')->nullable();
            $table->text('NOMBRE_CATEGORIA_ERGO')->nullable();
            $table->text('RECPSICO_NOMBRECATEGORIA')->nullable();
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
