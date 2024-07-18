<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCatsustanciasQuimicas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catsustancias_quimicas', function (Blueprint $table) {


            $table->increments('ID_SUTANCIA_QUIMICA');
            $table->text('SUSTANCIA_QUIMICA')->nullable();
            $table->text('ALTERACION_EFECTO')->nullable();
            $table->text('NUM_CAS')->nullable();
            $table->text('CONNOTACION')->nullable();
            $table->text('VLE_PPT')->nullable();
            $table->text('VLE_CT_P')->nullable();
            $table->text('NORMATIVIDAD')->nullable();
            $table->text('TEMPERATURA_EBULLICION')->nullable();
            $table->text('VOLATILIDAD')->nullable();  
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
