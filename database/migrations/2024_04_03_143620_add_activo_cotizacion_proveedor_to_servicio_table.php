<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActivoCotizacionProveedorToServicioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('servicio', function (Blueprint $table) {
            $table->boolean('ACTIVO_COTIZACIONPROVEEDOR')->default(1);
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    
}
