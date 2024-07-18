<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditarTablaClientesNuevoscamposRec extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cliente', function (Blueprint $table) {
            $table->integer('region_id')->nullable();
            $table->integer('subdireccion_id')->nullable();
            $table->integer('gerencia_id')->nullable();
            $table->integer('activo_id')->nullable();
            $table->text('cliente_instalacion')->nullable();
            $table->text('cliente_departamento')->nullable();


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
