<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablaPsicocatRecomendacionescontrol extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('psicocat_recomendacionescontrol', function (Blueprint $table) {
            $table->increments('ID_RECOMENDACION_CONTROL_INFORME');
            $table->text('RECOMENDACION_CONTROL')->nullable();
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
