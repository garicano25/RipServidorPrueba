<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablaReportenom0353categoria extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reportenom0352categoria', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('proyecto_id')->nullable();
            $table->integer('registro_id')->nullable();
            $table->integer('recpsicocategoria_id')->nullable();
            $table->text('reportenom0352categoria_nombre')->nullable();
            $table->integer('reportenom0352categoria_total')->nullable();
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
