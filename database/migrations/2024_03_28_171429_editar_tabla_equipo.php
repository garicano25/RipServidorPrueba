<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditarTablaEquipo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('equipo', function (Blueprint $table) {
            $table->dropColumn('equipo_CertificadoPDF');
            $table->dropColumn('equipo_cartaPDF');
        });

        Schema::table('equipo', function (Blueprint $table) {
            $table->text('folio_factura')->nullable();
            $table->text('equipo_imagen')->nullable();

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
