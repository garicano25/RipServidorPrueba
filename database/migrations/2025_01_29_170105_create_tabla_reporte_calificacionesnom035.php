<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablaReporteCalificacionesnom035 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reporte_calificacionestrabajadornom035', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('proyecto_id')->nullable();
            $table->integer('registro_id')->nullable();
            $table->integer('TRABAJADOR_ID')->nullable();
            $table->integer('ACONTECIMIENTO_CALIFICACION')->nullable();
            $table->integer('RECUERDOS_CALIFICACION')->nullable();
            $table->integer('ESFUERZO_CALIFICACION')->nullable();
            $table->integer('AFECTACION_CALIFICACION')->nullable();
            $table->integer('GUIA1_CALIFICACION')->nullable();
            $table->integer('C_AMBIENTE_CALIFICACION')->nullable();
            $table->integer('D_CONDICIONES_CALIFICACION')->nullable();
            $table->integer('C_AMBIENTE_NIVEL')->nullable();
            $table->integer('D_CONDICIONES_NIVEL')->nullable();
            $table->integer('C_FACTORES_CALIFICACION')->nullable();
            $table->integer('D_CARGA_CALIFICACION')->nullable();
            $table->integer('D_FALTA_CALIFICACION')->nullable();
            $table->integer('C_FACTORES_NIVEL')->nullable();
            $table->integer('D_CARGA_NIVEL')->nullable();
            $table->integer('D_FALTA_NIVEL')->nullable();
            $table->integer('C_FALTA_CALIFICACION')->nullable();
            $table->integer('C_FALTA_NIVEL')->nullable();
            $table->integer('C_ORGANIZACION_CALIFICACION')->nullable();
            $table->integer('D_JORNADA_CALIFICACION')->nullable();
            $table->integer('D_INTERFERENCIA_CALIFICACION')->nullable();
            $table->integer('C_ORGANIZACION_NIVEL')->nullable();
            $table->integer('D_JORNADA_NIVEL')->nullable();
            $table->integer('D_INTERFERENCIA_NIVEL')->nullable();
            $table->integer('C_LIDERAZGO_CALIFICACION')->nullable();
            $table->integer('D_LIDERAZGO_CALIFICACION')->nullable();
            $table->integer('D_RELACIONES_CALIFICACION')->nullable();
            $table->integer('D_VIOLENCIA_CALIFICACION')->nullable();
            $table->integer('C_LIDERAZGO_NIVEL')->nullable();
            $table->integer('D_LIDERAZGO_NIVEL')->nullable();
            $table->integer('D_RELACIONES_NIVEL')->nullable();
            $table->integer('D_VIOLENCIA_NIVEL')->nullable();
            $table->integer('C_ENTORNO_CALIFICACION')->nullable();
            $table->integer('D_RECONOCIMIENTO_CALIFICACION')->nullable();
            $table->integer('D_INSUFICIENTE_CALIFICACION')->nullable();
            $table->integer('C_ENTORNO_NIVEL')->nullable();
            $table->integer('D_RECONOCIMIENTO_NIVEL')->nullable();
            $table->integer('D_INSUFICIENTE_NIVEL')->nullable();
            $table->integer('GLOBAL_CALIFICACION')->nullable();
            $table->integer('GLOBAL_NIVEL')->nullable();
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
