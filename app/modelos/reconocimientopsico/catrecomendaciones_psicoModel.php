<?php

namespace App\modelos\reconocimientopsico;

use Illuminate\Database\Eloquent\Model;

class catrecomendaciones_psicoModel extends Model
{
    protected $primaryKey = 'ID_RECOMENDACION_INFORME';
    protected $table = 'psicocat_recomendaciones';
    protected $fillable = [
        'CATEGORIA',
        'NIVELRIESGO',
        'RECOMENDACION',
        'ACTIVO'
    ];
}
