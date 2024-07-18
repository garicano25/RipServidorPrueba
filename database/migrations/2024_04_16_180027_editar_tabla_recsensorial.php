<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditarTablaRecsensorial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recsensorial', function (Blueprint $table) {
            $table->integer('contrato_id');
            $table->text('recsensorial_ordenTrabajoLicitacion')->nullable();
            $table->text('json_personas_elaboran');
            $table->boolean('informe_del_cliente')->default(1);

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
