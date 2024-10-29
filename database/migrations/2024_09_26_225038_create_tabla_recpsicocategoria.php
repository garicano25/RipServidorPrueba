<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablaRecpsicocategoria extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recpsicocategoria', function (Blueprint $table) {
            $table->increments('RECPSICOCATEGORIA_ID');
            $table->integer('RECPSICO_ID')->nullable();
            $table->integer('catdepartamento_id')->nullable();
            $table->integer('catmovilfijo_id')->nullable();
            $table->text('RECPSICO_NOMBRECATEGORIA')->nullable();
            $table->integer('SUMAHORASJORNADA')->nullable();
            $table->text('JSON_TURNOS')->nullable();
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
