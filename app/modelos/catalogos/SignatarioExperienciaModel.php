<?php

namespace App\modelos\catalogos;

use Illuminate\Database\Eloquent\Model;

class SignatarioExperienciaModel extends Model
{
    protected $primaryKey = 'ID_EXPERIENCIA';
    protected $table = 'signatarios_experiencias';
    protected $fillable = [
        'PROVEEDOR_ID',
        'SIGNATARIO_ID',
        'NOMBRE_EMPRESA',
        'CARGO',
        'EXPERIENCIA_PDF',
        'FECHA_INICIO',
        'FECHA_FIN',
        'ELIMINADO'
    ];
}
