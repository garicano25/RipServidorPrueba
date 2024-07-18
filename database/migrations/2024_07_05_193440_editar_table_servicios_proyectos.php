<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditarTableServiciosProyectos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('serviciosProyecto', function (Blueprint $table) {
            $table->boolean('ERGO')->default(0);
            $table->boolean('ERGO_PROGRAMA')->default(0);
            $table->boolean('ERGO_RECONOCIMIENTO')->default(0);
            $table->boolean('ERGO_EJECUCION')->default(0);
            $table->boolean('ERGO_INFORME')->default(0);
            $table->boolean('PSICO')->default(0);
            $table->boolean('PSICO_PROGRAMA')->default(0);
            $table->boolean('PSICO_RECONOCIMIENTO')->default(0);
            $table->boolean('PSICO_EJECUCION')->default(0);
            $table->boolean('PSICO_INFORME')->default(0);
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
