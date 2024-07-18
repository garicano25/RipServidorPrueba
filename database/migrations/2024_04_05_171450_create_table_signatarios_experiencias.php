<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSignatariosExperiencias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('signatarios_experiencias', function (Blueprint $table) {
          

            $table->increments('ID_EXPERIENCIA');
            // $table->integer('PROVEEDOR_ID')->unsigned(); SE AGREGO LA FK DE PROVEEDOR_ID DE FORMA MANUAL
            // $table->integer('SIGNATARIO_ID')->unsigned(); SE AGREGO LA FK DE SIGNATARIO_ID DE FORMA MANUAL

            $table->text('NOMBRE_EMPRESA')->nullable();
            $table->text('CARGO')->nullable();
            $table->text('EXPERIENCIA_PDF')->nullable();
            $table->date('FECHA_INICIO')->nullable();
            $table->date('FECHA_FIN')->nullable();
            $table->boolean('ELIMINADO')->default(0);
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
