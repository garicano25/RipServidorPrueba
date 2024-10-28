<?php

namespace App\modelos\reconocimientopsico;

use Illuminate\Database\Eloquent\Model;

class recopsicoareaModel extends Model
{
    //
    protected $primaryKey = 'ID_RECOPSICOAREA';
    protected $table = 'recopsicoarea';
    protected $fillable = [
        'RECPSICO_ID',
        'RECPSICOAREA_NOMBRE',
        'RECPSICOAREA_PROCESO'
    ];
}
