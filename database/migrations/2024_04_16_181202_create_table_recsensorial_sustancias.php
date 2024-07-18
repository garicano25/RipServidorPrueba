<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRecsensorialSustancias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recsensorial_sustancias', function (Blueprint $table) {

            $table->increments('ID_RECSENSORIAL_SUSTANCIA');
            $table->integer('RECSENSORIAL_ID');
            $table->integer('SUSTANCIA_QUIMICA_ID');
            $table->integer('CANTIDAD')->nullable();
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
