<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class areasAfectadas_fuentesGeneradorasModel extends Model
{
    protected $primaryKey = 'ID_AFECTA_FUENTE';
    protected $table = 'areasAfectadas_fuentesGeneradoras';
    protected $fillable = [
        'FUENTE_GENERADORA_ID',
        'TIPO_ALCANCE',
        'PRUEBA_ID',
        'TIPO',
        'ACTIVO'
    ];
}
