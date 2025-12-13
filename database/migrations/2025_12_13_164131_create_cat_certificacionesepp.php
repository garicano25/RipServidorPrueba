<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatCertificacionesepp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cat_certificacionespp', function (Blueprint $table) {
            $table->increments('ID_CERTIFICACIONES_EPP');
            $table->text('NOMBRE_CERTIFICACION')->nullable();
            $table->text('DESCRIPCION_CERTIFICACION')->nullable();
            $table->text('FOTO_CERTIFICACION')->nullable();
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
