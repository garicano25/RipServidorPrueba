<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToRecsensorialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recsensorial', function (Blueprint $table) {
            $table->unsignedInteger('ordentrabajo_id')->nullable();
            $table->unsignedInteger('proyecto_id')->nullable();
            $table->string('recsensorial_documentocliente', 250)->nullable();
            $table->string('recsensorial_personaelaboro', 255)->nullable();
            $table->date('recsensorial_fechaelaboracion')->nullable();
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
