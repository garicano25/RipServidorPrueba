<?php

namespace App\modelos\reconocimientopsico;

use Illuminate\Database\Eloquent\Model;

class catintroducciones_psicoModel extends Model
{
    protected $primaryKey = 'ID_INTRODUCCION_INFORME';
    protected $table = 'psicocat_introducciones';
    protected $fillable = [
        'INTRODUCCION',
        'ACTIVO'
    ];
}
