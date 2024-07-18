<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveColumnsFromRecsensorialcategoriaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recsensorialcategoria', function (Blueprint $table) {
            $table->dropColumn([
                'recsensorialcategoria_funcioncategoria',
                'recsensorialcategoria_horasjornada',
                'recsensorialcategoria_horarioentrada',
                'recsensorialcategoria_horariosalida'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
}