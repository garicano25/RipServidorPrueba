<?php

namespace App\modelos\reconocimientopsico;

use Illuminate\Database\Eloquent\Model;

class catdefiniciones_psicoModel extends Model
{
    protected $primaryKey = 'ID_DEFINICION_INFORME';
    protected $table = 'psicocat_definiciones';
    protected $fillable = [
        'CONCEPTO',
        'DESCRIPCION',
        'FUENTE',
        'ACTIVO'
    ];
}
