<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgregarcamposTablaclientes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cliente', function (Blueprint $table) {

            $table->text('requiere_estructuraCliente_switch')->nullable();
            $table->text('organizacional1_etiqueta')->nullable();
            $table->text('organizacional1_opciones')->nullable();
            $table->text('organizacional2_etiqueta')->nullable();
            $table->text('organizacional2_opciones')->nullable();
            $table->text('organizacional3_etiqueta')->nullable();
            $table->text('organizacional3_opciones')->nullable();
            $table->text('organizacional4_etiqueta')->nullable();
            $table->text('organizacional4_opciones')->nullable();
            $table->text('organizacional5_etiqueta')->nullable();
            $table->text('organizacional5_opciones')->nullable();

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
