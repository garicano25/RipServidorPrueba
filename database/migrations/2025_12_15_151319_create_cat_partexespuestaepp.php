<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatPartexespuestaepp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cat_partexespuestaepp', function (Blueprint $table) {
           
                $table->increments('ID_PARTE_EXPUESTO');
                $table->text('NOMBRE_PARTE')->nullable();
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
