<?php

namespace App\modelos\clientes;

use Illuminate\Database\Eloquent\Model;

class clientedocumentoModel extends Model
{   
    #Se modifico la tabla de clientesdocuemntos a contratos_documentos
    protected $table = 'contratos_documentos';
    protected $fillable = [
          'cliente_id',
          'CONTRATO_ID'
        , 'clienteDocumento_Nombre'
        , 'clienteDocumento_SoportePDF'
        , 'clienteDocumento_Eliminado'
    ];
}
