<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddJsonTurnosToRecsensorialcategoriaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recsensorialcategoria', function (Blueprint $table) {
            $table->json('JSON_TURNOS')->nullable();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    
}
