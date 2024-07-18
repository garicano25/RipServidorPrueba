<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditTableCatsustanciaQuimicaV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('catsustancias_quimicas', function (Blueprint $table) {
            
            $table->text('VOLATILIDAD');
            $table->text('ESTADO_FISICO');
            $table->text('TEM_EBULLICION');
            $table->boolean('TIPO_CLASIFICACION')->default(1);
            $table->integer('CATEGORIA_PELIGRO_ID');
            $table->integer('GRADO_RIESGO_ID');
            $table->integer('CLASIFICACION_RIESGO');



          
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
