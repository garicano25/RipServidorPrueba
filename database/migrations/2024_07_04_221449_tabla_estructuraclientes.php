<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TablaEstructuraclientes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('estructuraclientes', function (Blueprint $table) {
        $table->integer('CLIENTES_ID');
        $table->text('ETIQUETA_ID')->nullable();
        $table->text('OPCIONES_ID')->nullable();
        $table->timestamps();

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
