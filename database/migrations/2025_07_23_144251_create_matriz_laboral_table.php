<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatrizLaboralTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matriz_laboral', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('proyecto_id');
            $table->unsignedBigInteger('area_id');
            $table->string('fila_id', 20);
            $table->string('agente')->nullable();
            $table->string('categoria')->nullable();
            $table->string('numero_trabajadores')->nullable();
            $table->string('tiempo_exposicion')->nullable();
            $table->string('indice_peligro')->nullable();
            $table->string('indice_exposicion')->nullable();
            $table->string('riesgo')->nullable();
            $table->string('valor_lmpnmp')->nullable();
            $table->string('cumplimiento')->nullable();
            $table->json('medidas_json')->nullable();
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
        Schema::dropIfExists('matriz_laboral');
    }
}
