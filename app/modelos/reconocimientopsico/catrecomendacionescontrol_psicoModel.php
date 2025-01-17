<?php

namespace App\modelos\reconocimientopsico;

use Illuminate\Database\Eloquent\Model;

class catrecomendacionescontrol_psicoModel extends Model
{
    protected $primaryKey = 'ID_RECOMENDACION_CONTROL_INFORME';
    protected $table = 'psicocat_recomendacionescontrol';
    protected $fillable = [
        'RECOMENDACION_CONTROL',
        'ACTIVO'
    ];
}
