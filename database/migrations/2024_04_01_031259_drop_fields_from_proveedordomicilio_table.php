<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropFieldsFromProveedordomicilioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('proveedordomicilio', function (Blueprint $table) {
            // Eliminar los campos de la tabla
            $table->dropColumn('proveedorDomicilio_Contacto');
            $table->dropColumn('proveedorDomicilio_Cargo');
            $table->dropColumn('proveedorDomicilio_Telefono');
            $table->dropColumn('proveedorDomicilio_Eliminado');
        });
    }
}
    /**
     * Reverse the migrations.
     *
     * @return void
     */
  