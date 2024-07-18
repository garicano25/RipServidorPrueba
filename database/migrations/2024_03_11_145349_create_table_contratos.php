<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableContratos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contratos_clientes', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            $table->increments('ID_CONTRATO');
            // $table->integer('CLIENTE_ID')->unsigned(); SE AGREGO LA FK DE CLIENTE_ID DE FORMA MANUAL
            
            $table->text('NOMBRE_CONTACTO');
            $table->text('CARGO_CONTACTO');
            $table->text('CORREO_CONTACTO');    
            $table->text('TELEFONO_CONTACTO');
            $table->text('CELULAR_CONTACTO');
            $table->text('NUMERO_CONTRATO');
            $table->text('DESCRIPCION_CONTRATO');
            $table->text('MONEDA_MONTO');
            $table->float('MONTO', 20, 2);
            $table->text('CONTRATO_PLANTILLA_LOGOIZQUIERDO');
            $table->text('CONTRATO_PLANTILLA_LOGODERECHO');
            $table->text('CONTRATO_PLANTILLA_ENCABEZADO');
            $table->text('CONTRATO_PLANTILLA_EMPRESARESPONSABLE');
            $table->text('CONTRATO_PLANTILLA_PIEPAGINA');
            $table->date('FECHA_INICIO');
            $table->date('FECHA_FIN');
            $table->boolean('ACTIVO')->default(1);
            $table->timestamps();

            // $table->foreign('CLIENTE_ID')->references('id')->on('cliente')->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contratos_clientes');
        
    }
}
