<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditarTablaProveedor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('proveedor', function (Blueprint $table) {
            $table->dropColumn('proveedor_NombreContacto');
            $table->dropColumn('proveedor_CargoContacto');
            $table->dropColumn('proveedor_CorreoContacto');
            $table->dropColumn('proveedor_TelefonoContacto');
            $table->dropColumn('proveedor_CelularContacto');

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
