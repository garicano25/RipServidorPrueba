<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRecsensorialdatosdelareaToRecsensorialareaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recsensorialarea', function (Blueprint $table) {
            $table->string('RECSENSORIAL_DATOSAREA')->nullable();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
   
}
