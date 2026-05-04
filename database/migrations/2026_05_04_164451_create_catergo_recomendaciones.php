<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatergoRecomendaciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catergo_recomendaciones', function (Blueprint $table) {
            $table->increments('ID_RECOMENDACIONES');
            $table->text('TIPO_RECOMENDACIONES')->nullable();
            $table->text('DESCRIPCION_RECOMENDACIONES')->nullable();
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
