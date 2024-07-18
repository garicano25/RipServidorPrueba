<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditarTablaRecsensorialmaquinaria extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recsensorialmaquinaria', function (Blueprint $table) {
            $table->integer('recsensorialmaquinaria_contenido')->nullable();
            $table->text('recsensorialmaquinaria_unidadMedida')->nullable();

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
