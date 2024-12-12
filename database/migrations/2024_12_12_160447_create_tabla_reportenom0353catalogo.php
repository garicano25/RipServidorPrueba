<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablaReportenom0353catalogo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reportenom0353catalogo', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('reportenom0353catalogo_catregion_activo')->nullable();
            $table->integer('reportenom0353catalogo_catsubdireccion_activo')->nullable();
            $table->integer('reportenom0353catalogo_catgerencia_activo')->nullable();
            $table->integer('reportenom0353catalogo_catactivo_activo')->nullable();
            $table->text('reportenom0353catalogo_introduccion')->nullable();
            $table->text('reportenom0353catalogo_objetivogeneral')->nullable();
            $table->text('reportenom0353catalogo_objetivoespecifico')->nullable();
            $table->text('reportenom0353catalogo_metodologia')->nullable();
            $table->text('reportenom0353catalogo_ubicacioninstalacion')->nullable();
            $table->text('reportenom0353catalogo_procesoelaboracion')->nullable();
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
