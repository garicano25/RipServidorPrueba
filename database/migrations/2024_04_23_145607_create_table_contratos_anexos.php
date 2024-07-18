<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableContratosAnexos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contratros_anexos', function (Blueprint $table) {

            $table->increments('ID_CONTRATO_ANEXO');
            $table->integer('CONTRATO_ID');
            $table->text('NOMBRE_ANEXO')->nullable();
            $table->text('TIPO')->nullable();
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
