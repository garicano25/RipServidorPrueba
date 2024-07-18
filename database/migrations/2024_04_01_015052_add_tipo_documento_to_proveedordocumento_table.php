<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTipoDocumentoToProveedordocumentoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('proveedordocumento', function (Blueprint $table) {
            $table->string('TIPO_DOCUMENTO')->nullable();
        });
    }
    
}

    /**
     * Reverse the migrations.
     *
    
