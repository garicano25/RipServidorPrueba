<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCatpartescuerpoDescripcion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catpartescuerpo_descripcion', function (Blueprint $table) {

            $table->increments('ID_PARTESCUERPO_DESCRIPCION');
            $table->integer('PARTECUERPO_ID');
            $table->text('CLAVE_EPP')->nullable();
            $table->text('TIPO_RIEGO')->nullable();
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
