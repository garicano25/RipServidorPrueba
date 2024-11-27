<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablaPsicocatConclusiones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('psicocat_conclusiones', function (Blueprint $table) {
            $table->increments('ID_CONCLUSION_INFORME');
            $table->text('NOMBRE')->nullable();
            $table->text('CONCLUSION')->nullable();
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
