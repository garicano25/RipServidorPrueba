<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatEpps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cat_epps', function (Blueprint $table) {
            $table->increments('ID_CAT_EPP');
            $table->text('FOTO_EPP')->nullable();
            $table->text('REGION_ANATOMICA_EPP')->nullable();
            $table->text('CLAVEYEPP_EPP')->nullable();
            $table->text('TIPO_RIESGO_EPP')->nullable();
            $table->text('NOMBRE_EPP')->nullable();
            $table->text('MARCA_EPP')->nullable();
            $table->text('MODELO_EPP')->nullable();
            $table->text('NORMASNACIONALES_EPP')->nullable();
            $table->text('APARTADONOMNACIONALES_EPP')->nullable();
            $table->text('NORMASINTERNACIONALES_EPP')->nullable();
            $table->text('APARTADONOMINTERNACIONALES_EPP')->nullable();
            $table->text('CERTIFICACIONES_ADICIONALES_EPP')->nullable();
            $table->text('TIPO_GRADO_EPP')->nullable();
            $table->text('FABRICATALLAS_EPP')->nullable();
            $table->text('TALLAS_EPP')->nullable();
            $table->text('RECOMENDACIONES_TALLAS_EPP')->nullable();
            $table->text('TRABAJADORES_DISCAPACIDAD_EPP')->nullable();
            $table->text('ESPECIFIQUE_FORMA_EPP')->nullable();
            $table->text('CLASIFICACION_RIESGO_EPP')->nullable();
            $table->text('CUAL_CLASIFICACION_EPP')->nullable();
            $table->text('TIPO_USO_EPP')->nullable();
            $table->text('PARTE_EXPUESTA_EPP')->nullable();
            $table->text('RECOMENDACIONES_USO_EPP')->nullable();
            $table->text('RESTRICCIONES_USO_EPP')->nullable();
            $table->text('REQUIERE_AJUSTE_EPP')->nullable();
            $table->text('ESPECIFIQUE_AJUSTE_EPP')->nullable();
            $table->text('RECOMENDACION_ALMACENAMIENTO_EPP')->nullable();
            $table->text('UTILIZAR_EMERGENCIA_EPP')->nullable();
            $table->text('ESPECIFIQUE_EMERGENCIA_EPP')->nullable();
            $table->text('COMPATIBILIDAD_EPPS')->nullable();
            $table->text('INCOMPATIBILIDAD_EPPS')->nullable();
            $table->text('INSPECCION_INTERNA_EPP')->nullable();
            $table->text('FRECUENCIA_INTERNA_EPP')->nullable();
            $table->text('RESPONSABLE_INTERNA_EPP')->nullable();
            $table->text('INSPECCION_EXTERNA_EPP')->nullable();
            $table->text('FRECUENCIA_EXTERNA_EPP')->nullable();
            $table->text('RESPONSABLE_EXTERNA_EPP')->nullable();
            $table->text('RECOMENDACION_LIMPIEZA_EPPS')->nullable();
            $table->text('PROCEDIMIENTO_DESCONTAMINACION_EPP')->nullable();
            $table->text('DESCONTAMINACION_ESPECIFIQUE_EPP')->nullable();
            $table->text('VIDA_UTIL_EPP')->nullable();
            $table->text('CRITERIOS_DESECHAR_EPP')->nullable();
            $table->text('RECOMENDACION_DISPOSICION_EPPS')->nullable();
            $table->text('CARACTERISTICAS_ESPECIFICAS_EPP')->nullable();
            $table->text('MATERIALES_UTILIZADOS_EPP')->nullable();
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
