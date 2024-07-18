<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToProveedordomicilioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('proveedordomicilio', function (Blueprint $table) {
            // Agregar campos a la tabla
            $table->string('proveedorDomicilio_ciudad')->nullable();
            $table->string('contactos_sucursal')->nullable();
        });
    }

}
    /**
     * Reverse the migrations.
     *
     * @return void
     */
  