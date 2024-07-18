<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class recsensorialSustanciasModel extends Model
{
    protected $primaryKey = 'ID_RECSENSORIAL_SUSTANCIA';
    protected $table = 'recsensorial_sustancias';
    protected $fillable = [
        'RECSENSORIAL_ID',
        'SUSTANCIA_QUIMICA_ID',
        'CANTIDAD',
    ];
}
