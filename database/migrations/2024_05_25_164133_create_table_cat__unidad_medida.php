<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCatUnidadMedida extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cat_UnidadMedida', function (Blueprint $table) {

            $table->increments('ID_UNIDAD_MEDIDA');
            $table->text('DESCRIPCION');
            $table->text('ABREVIATURA');
            $table->boolean('ACTIVO')->default(1);
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
