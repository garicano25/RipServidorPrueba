<?php

namespace App\modelos\reconocimientopsico;

use Illuminate\Database\Eloquent\Model;

class catcargos_psicoModel extends Model
{
    protected $primaryKey = 'ID_CARGO_INFORME';
    protected $table = 'psicocat_cargos';
    protected $fillable = [
        'CARGO',
        'ACTIVO'
    ];
}
