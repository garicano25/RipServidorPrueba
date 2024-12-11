<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablaReportenom0353area extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reportenom0353area', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('proyecto_id')->nullable();
            $table->integer('registro_id')->nullable();
            $table->integer('recpsicoarea_id')->nullable();
            $table->text('reportenom0353area_instalacion')->nullable();
            $table->text('reportenom0353area_nombre')->nullable();
            $table->integer('reportenom0353area_numorden')->nullable();
            $table->double('reportenom0353area_porcientooperacion')->nullable();
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
