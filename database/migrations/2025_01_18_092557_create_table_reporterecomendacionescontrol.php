<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableReporterecomendacionescontrol extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reporterecomendacionescontrol', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('proyecto_id')->nullable();
            $table->integer('registro_id')->nullable();
            $table->text('descripcion')->nullable();
            $table->integer('reporterecomendacionescatalogo_id')->nullable();
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
