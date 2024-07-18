<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RestoreFieldsToProveedordomicilioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('proveedordomicilio', function (Blueprint $table) {
            // Agregar los campos eliminados nuevamente a la tabla
            $table->string('proveedorDomicilio_Contacto')->nullable();
            $table->string('proveedorDomicilio_Cargo')->nullable();
            $table->string('proveedorDomicilio_Telefono')->nullable();
            $table->boolean('proveedorDomicilio_Eliminado')->default(0);
        });
    }
}
    /**
     * Reverse the migrations.
     *
     * @return void
     */
   
