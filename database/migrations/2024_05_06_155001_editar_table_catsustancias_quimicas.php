<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditarTableCatsustanciasQuimicas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('catsustancias_quimicas', function (Blueprint $table) {
            $table->dropColumn('NORMATIVIDAD');
        });

        Schema::table('catsustancias_quimicas', function (Blueprint $table) {
            $table->integer('ENTIDAD');
            $table->integer('DESCRIPCION_NORMATIVA');
            $table->text('JSON_BEIS');


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
