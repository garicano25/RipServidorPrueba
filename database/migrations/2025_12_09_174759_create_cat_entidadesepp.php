<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatEntidadesepp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cat_entidadesepp', function (Blueprint $table) {
            $table->increments('ID_ENTIDAD_EPP');
            $table->text('NOMBRE_ENTIDAD')->nullable();
            $table->text('ENTIDAD_DESCRIPCION')->nullable();
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
