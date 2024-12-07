<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablaReportenom0352 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reportenom0353', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('proyecto_id')->nullable();
            $table->integer('agente_id')->nullable();
            $table->integer('agente_nombre')->nullable();
            $table->integer('reportenom0353_revision')->nullable();
            $table->text('reporte_mes')->nullable();
            $table->text('reportenom0353_fecha')->nullable();
            $table->text('reportenom0353_instalacion')->nullable();
            $table->integer('reportenom0353_catregion_activo')->nullable();
            $table->integer('reportenom0353_catsubdireccion_activo')->nullable();
            $table->integer('reportenom0353_catgerencia_activo')->nullable();
            $table->integer('reportenom0353_catactivo_activo')->nullable();
            $table->text('reportenom0353_introduccion')->nullable();
            $table->text('reportenom0353_objetivogeneral')->nullable();
            $table->text('reportenom0353_objetivoespecifico')->nullable();
            $table->text('reportenom0353_metodologiainstrumentos')->nullable();
            $table->text('reportenom0353_ubicacioninstalacion')->nullable();
            $table->text('reportenom0353_ubicacionfoto')->nullable();
            $table->text('reportenom0353_procesoinstalacion')->nullable();
            $table->text('reportenom0353_metodoevaluacion')->nullable();
            $table->text('reportenom0353_datosdemograficos')->nullable();
            $table->text('reportenom0353_interpretacion1')->nullable();
            $table->text('reportenom0353_interpretacion2')->nullable();
            $table->text('reportenom0353_interpretacion3')->nullable();
            $table->text('reportenom0353_interpretacion4')->nullable();
            $table->text('reportenom0353_interpretacion5')->nullable();
            $table->text('reportenom0353_interpretacion6')->nullable();
            $table->text('reportenom0353_interpretacion7')->nullable();
            $table->text('reportenom0353_conclusion')->nullable();
            $table->text('reportenom0353_responsable1')->nullable();
            $table->text('reportenom0353_responsable1cargo')->nullable();
            $table->text('reportenom0353_responsable1documento')->nullable();
            $table->text('reportenom0353_responsable2')->nullable();
            $table->text('reportenom0353_responsable2cargo')->nullable();
            $table->text('reportenom0353_responsable2doc')->nullable();
            $table->integer('reportenom0353_concluido')->nullable();
            $table->text('reportenom0353_concluidonombre')->nullable();
            $table->datetime('reportenom0353_concluidofecha')->nullable();
            $table->integer('reportenom0353_cancelado')->nullable();
            $table->text('reportenom0353_canceladonombre')->nullable();
            $table->datetime('reportenom0353_canceladofecha')->nullable();
            $table->text('reportenom0353_canceladoobservacion')->nullable();
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
