<?php

namespace App\modelos\recsensorialquimicos;

use Illuminate\Database\Eloquent\Model;

class catSustanciasQuimicasModel extends Model
{
    protected $primaryKey = 'ID_SUSTANCIA_QUIMICA';
    protected $table = 'catsustancias_quimicas';
    protected $fillable = [
        'SUSTANCIA_QUIMICA',
        'ALTERACION_EFECTO',
        'PM',
        'NUM_CAS',
        'VIA_INGRESO',
        'TIPO_CLASIFICACION',
        'CATEGORIA_PELIGRO_ID',
        'GRADO_RIESGO_ID',
        'CLASIFICACION_RIESGO',
        'ACTIVO'
    ];
}
