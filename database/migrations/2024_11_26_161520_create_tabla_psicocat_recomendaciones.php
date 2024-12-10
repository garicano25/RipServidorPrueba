<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablaPsicocatRecomendaciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('psicocat_recomendaciones', function (Blueprint $table) {
            $table->increments('ID_RECOMENDACION_INFORME');
            $table->integer('NIVEL')->nullable();
            $table->text('RECOMENDACION')->nullable();
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
