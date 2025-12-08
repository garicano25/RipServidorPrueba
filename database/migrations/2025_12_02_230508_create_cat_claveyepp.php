<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatClaveyepp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cat_claveyepp', function (Blueprint $table) {
            $table->increments('ID_CLAVE_EPP');
            $table->text('REGION_ANATOMICA_ID')->nullable();
            $table->text('CLAVE')->nullable();
            $table->text('EPP')->nullable();
            $table->text('TIPO_RIESGO')->nullable();
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
