<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecoergoarea extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recoergoareas', function (Blueprint $table) {

            $table->increments('ID_AREA_ERGO');
            $table->text('RECO_ID')->nullable();
            $table->text('NOMBRE_AREA_ERGO')->nullable();
            $table->text('DESCRIPCION_AREA_ERGO')->nullable();
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
