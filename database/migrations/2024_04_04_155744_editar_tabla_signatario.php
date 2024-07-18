<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditarTablaSignatario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('signatario', function (Blueprint $table) {
            $table->boolean('signatario_apoyo')->default(1);
            $table->text('signatario_Alergias')->nullable();
            $table->text('signatario_telEmergencia')->nullable();
            $table->text('signatario_NombreContacto')->nullable();
            $table->text('signatario_parentesco')->nullable();

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
