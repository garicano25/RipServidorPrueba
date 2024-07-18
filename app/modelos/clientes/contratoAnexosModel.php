<?php

namespace App\modelos\clientes;

use Illuminate\Database\Eloquent\Model;

class contratoAnexosModel extends Model
{
    protected $primaryKey = 'ID_CONTRATO_ANEXO';
    protected $table = 'contratros_anexos';
    protected $fillable = [
        'CONTRATO_ID',
        'NOMBRE_ANEXO',
        'TIPO',
        'ACTIVO'
    ];
}
