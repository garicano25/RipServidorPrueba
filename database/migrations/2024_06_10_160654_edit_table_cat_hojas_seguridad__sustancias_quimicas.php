<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditTableCatHojasSeguridadSustanciasQuimicas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('catHojasSeguridad_SustanciasQuimicas', function (Blueprint $table) {

            $table->text('OPERADOR');
            $table->float('PORCENTAJE');
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
