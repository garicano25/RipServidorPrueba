<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditarRecsensorialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recsensorial', function (Blueprint $table) {
            $table->string('recsensorial_documentoclientevalidacion', 250)->nullable();
            $table->string('recsensorial_personavalido', 255)->nullable();
            $table->date('recsensorial_fechavalidacion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recsensorial', function (Blueprint $table) {
            //
        });
    }
}
