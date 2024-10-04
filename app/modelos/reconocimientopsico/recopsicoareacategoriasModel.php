<?php

namespace App\modelos\reconocimientopsico;

use Illuminate\Database\Eloquent\Model;

class recopsicoareacategoriasModel extends Model
{
    //
    protected $primaryKey = 'ID_RECOPSICOAREACATEGORIAS';
    protected $table = 'recopsicoareacategorias';
    protected $fillable = [
        'RECPSICOAREA_ID',
        'RECPSICOCATEGORIA_ID',
        'RECPSICOAREACATEGORIAS_ACTIVIDAD',
        'RECPSICOAREACATEGORIAS_GEH',
        'RECPSICOAREACATEGORIAS_TOTAL'
    ];
}
