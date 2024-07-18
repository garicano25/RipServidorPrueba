<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditarTablaRecsensorialequipopp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recsensorialequipopp', function (Blueprint $table) {
            $table->dropColumn('recsensorialequipopp_descripcion');
        });

        Schema::table('recsensorialequipopp', function (Blueprint $table) {
            $table->integer('catpartescuerpo_descripcion_id');
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
