<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablaReportenom0352new extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reportenom0352', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('proyecto_id')->nullable();
            $table->integer('agente_id')->nullable();
            $table->integer('agente_nombre')->nullable();
            $table->integer('reportenom0352_revision')->nullable();
            $table->text('reporte_mes')->nullable();
            $table->text('reportenom0352_fecha')->nullable();
            $table->text('reportenom0352_instalacion')->nullable();
            $table->integer('reportenom0352_catregion_activo')->nullable();
            $table->integer('reportenom0352_catsubdireccion_activo')->nullable();
            $table->integer('reportenom0352_catgerencia_activo')->nullable();
            $table->integer('reportenom0352_catactivo_activo')->nullable();
            $table->text('reportenom0352_introduccion')->nullable();
            $table->text('reportenom0352_objetivogeneral')->nullable();
            $table->text('reportenom0352_objetivoespecifico')->nullable();
            $table->text('reportenom0352_metodologiainstrumentos')->nullable();
            $table->text('reportenom0352_ubicacioninstalacion')->nullable();
            $table->text('reportenom0352_ubicacionfoto')->nullable();
            $table->text('reportenom0352_procesoinstalacion')->nullable();
            $table->text('reportenom0352_metodoevaluacion')->nullable();
            $table->text('reportenom0352_datosdemograficos')->nullable();
            $table->text('reportenom0352_interpretacion1')->nullable();
            $table->text('reportenom0352_interpretacion2')->nullable();
            $table->text('reportenom0352_interpretacion3')->nullable();
            $table->text('reportenom0352_interpretacion4')->nullable();
            $table->text('reportenom0352_interpretacion5')->nullable();
            $table->text('reportenom0352_interpretacion6')->nullable();
            $table->text('reportenom0352_interpretacion7')->nullable();
            $table->text('reportenom0352_conclusion')->nullable();
            $table->text('reportenom0352_responsable1')->nullable();
            $table->text('reportenom0352_responsable1cargo')->nullable();
            $table->text('reportenom0352_responsable1documento')->nullable();
            $table->text('reportenom0352_responsable2')->nullable();
            $table->text('reportenom0352_responsable2cargo')->nullable();
            $table->text('reportenom0352_responsable2doc')->nullable();
            $table->integer('reportenom0352_concluido')->nullable();
            $table->text('reportenom0352_concluidonombre')->nullable();
            $table->datetime('reportenom0352_concluidofecha')->nullable();
            $table->integer('reportenom0352_cancelado')->nullable();
            $table->text('reportenom0352_canceladonombre')->nullable();
            $table->datetime('reportenom0352_canceladofecha')->nullable();
            $table->text('reportenom0352_canceladoobservacion')->nullable();
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
