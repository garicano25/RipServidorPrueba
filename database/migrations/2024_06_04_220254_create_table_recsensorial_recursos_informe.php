<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRecsensorialRecursosInforme extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recsensorial_recursos_informes', function (Blueprint $table) {

            $table->increments('ID_RECURSO_INFORME');
            $table->integer('RECSENSORIAL_ID');
            $table->text('INTRODUCCION');
            $table->text('METODOLOGIA');
            $table->text('CONCLUSION');
            $table->text('IMAGEN_PORTADA');
            $table->boolean('REGION')->default(1);
            $table->boolean('SUBDIRRECCION')->default(1);
            $table->boolean('GERENCIA')->default(1);
            $table->boolean('ACTIVO')->default(1);
            $table->boolean('INSTALACION')->default(1);
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
