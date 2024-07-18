<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class catCargosInformeModel extends Model
{
    protected $primaryKey = 'ID_CARGO_INFORME';
    protected $table = 'cat_cargosInforme';
    protected $fillable = [
        'CARGO',
        'ACTIVO'
    ];
}
