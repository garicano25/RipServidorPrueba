<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditarTablaClientes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cliente', function (Blueprint $table) {
            $table->dropColumn('cliente_NombreContacto');
            $table->dropColumn('cliente_CargoContacto');
            $table->dropColumn('cliente_CorreoContacto');
            $table->dropColumn('cliente_TelefonoContacto');
            $table->dropColumn('cliente_CelularContacto');
            $table->dropColumn('cliente_numerocontrato');
            $table->dropColumn('cliente_descripcioncontrato');
            $table->dropColumn('cliente_fechainicio');
            $table->dropColumn('cliente_fechafin');
            $table->dropColumn('cliente_montomxn');
            $table->dropColumn('cliente_montousd');
            $table->dropColumn('cliente_plantillalogoizquierdo');
            $table->dropColumn('cliente_plantillalogoderecho');
            $table->dropColumn('cliente_plantillaencabezado');
            $table->dropColumn('cliente_plantillaempresaresponsable');
            $table->dropColumn('cliente_plantillapiepagina');
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
