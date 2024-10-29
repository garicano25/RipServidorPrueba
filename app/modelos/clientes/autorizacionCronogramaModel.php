<?php

namespace App\modelos\clientes;

use Illuminate\Database\Eloquent\Model;

class autorizacionCronogramaModel extends Model
{
    protected $primaryKey = 'ID_AUTORIZACION';
    protected $table = 'autorizacionCronograma';
    protected $fillable = [
        'CONTRATO_ID',
        'PROYECTO_ID',
        'FECHA_VALIDACION_CRONOGRAMA',
        'CARGO_VALIDACION_CRONOGRAMA',
        'NOMBRE_VALIDACION_CRONOGRAMA',    
        'FECHA_AUTORIZACION_CRONOGRAMA',
        'CARGO_AUTORIZACION_CRONOGRAMA',
        'NOMBRE_AUTORIZACION_CRONOGRAMA',
    ];
}
