<?php

namespace App\modelos\clientes;

use Illuminate\Database\Eloquent\Model;

class clienteconvenioModel extends Model
{
    #Este modelo representa a la tabla de contratos_convenios
    protected $table = 'contratos_convenios';
    protected $fillable = [
          'CONTRATO_ID'
        , 'clienteconvenio_montomxn'
        , 'clienteconvenio_montousd'
        , 'clienteconvenio_vigencia'
    ];
}
