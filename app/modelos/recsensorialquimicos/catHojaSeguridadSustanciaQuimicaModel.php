<?php

namespace App\modelos\recsensorialquimicos;

use Illuminate\Database\Eloquent\Model;

class catHojaSeguridadSustanciaQuimicaModel extends Model
{
    protected $primaryKey = 'ID_HOJA_SUSTANCIA';
    protected $table = 'catHojasSeguridad_SustanciasQuimicas';
    protected $fillable = [
        'HOJA_SEGURIDAD_ID',
        'SUSTANCIA_QUIMICA_ID',
        'PORCENTAJE',
        'OPERADOR',
        'TEM_EBULLICION',
        'VOLATILIDAD',
        'ESTADO_FISICO',
        'FORMA',
        'ACTIVO'
    ];
}
