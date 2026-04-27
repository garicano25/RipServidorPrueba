<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecoergoFichastecnicas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recoergo_fichastecnicas', function (Blueprint $table) {
            $table->increments('ID_FICHAS_TECNICAS');
            $table->text('RECO_ID')->nullable();
            $table->text('CATEGORIA_ID_FICHA')->nullable();
            $table->text('CAT_DEPARTAMENTO_FICHA')->nullable();
            $table->text('CAT_AREAS_FICHA')->nullable();
            $table->text('NOMBRE_EMPLEADO_FICHA')->nullable();
            $table->text('NO_EMPLEADO_FICHA')->nullable();
            $table->date('SEXO_EMPLEADO_FICHA')->nullable();
            $table->text('FECHA_NACIMIENTO')->nullable();
            $table->text('EDAD_EMPLEADO_FICHA')->nullable();
            $table->text('PESO_FICHA')->nullable();
            $table->text('TALLA_FICHA')->nullable();
            $table->text('REGIMEN_CONTRACTUAL_FICHA')->nullable();
            $table->text('JORNADA_EMPLEADO_FICHA')->nullable();
            $table->text('TURNO_EMPLEADO_FICHA')->nullable();
            $table->text('TIEMPO_EMPRESA_FICHA')->nullable();
            $table->text('ANTIGUEDAD_CATEOGORIA_FICHA')->nullable();
            $table->text('P1_CARGA_MAYOR_3KG')->nullable();
            $table->text('P2_FRECUENCIA_CARGA')->nullable();
            $table->text('P3_MANIPULACION_CARGA')->nullable();
            $table->text('JSON_ACTIVIDADES')->nullable();
            $table->text('JSON_FICHAS')->nullable();
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
