<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablaReportenom0352catalogo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reportenom0352catalogo', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('reportenom0352catalogo_catregion_activo')->nullable();
            $table->integer('reportenom0352catalogo_catsubdireccion_activo')->nullable();
            $table->integer('reportenom0352catalogo_catgerencia_activo')->nullable();
            $table->integer('reportenom0352catalogo_catactivo_activo')->nullable();

            $table->text('reportenom0352catalogo_introduccion')->nullable();
            $table->text('reportenom0352catalogo_objetivogeneral')->nullable();

            $table->text('reportenom0352catalogo_objetivoespecifico')->nullable();
            $table->text('reportenom0352catalogo_metodologia_4_1')->nullable();
            $table->text('reportenom0352catalogo_ubicacioninstalacion')->nullable();
            $table->text('reportenom0352catalogo_descripcionproceso')->nullable();   
            $table->text('reportenom0352catalogo_metodologia_4')->nullable();
            $table->text('reportenom0352catalogo_metodologia_4')->nullable();
            $table->text('reportenom0352catalogo_metodologia_4')->nullable();
            $table->text('reportenom0352catalogo_metodologia_4')->nullable();

            
            $table->text('reportenom0352catalogo')->nullable();
            $table->text('reportenom0352catalogo')->nullable();

            $table->integer('reportenom0352catalogo')->nullable();
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
