<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablaRecopsicoarea extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    
        Schema::create('recopsicoarea', function (Blueprint $table) {
            $table->increments('ID_RECOPSICOAREA');
            $table->integer('RECPSICO_ID')->nullable();
            $table->text('RECPSICOAREA_NOMBRE')->nullable();
            $table->text('RECPSICOAREA_PROCESO')->nullable();
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
