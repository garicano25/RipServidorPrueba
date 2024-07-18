<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAreasAfectanFuentesGeneradoras extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('areasAfectadas_fuentesGeneradoras', function (Blueprint $table) {

            $table->increments('ID_AFECTA_FUENTE');
            $table->integer('FUENTE_GENERADORA_ID');
            $table->text('TIPO_ALCANCE');
            $table->integer('PRUEBA_ID');
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
