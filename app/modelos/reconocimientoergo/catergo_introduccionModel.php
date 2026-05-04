<?php

namespace App\modelos\reconocimientoergo;

use Illuminate\Database\Eloquent\Model;

class catergo_introduccionModel extends Model
{
    protected $primaryKey = 'ID_INTRODUCCION';
    protected $table = 'catergo_introduccion';
    protected $fillable = [
        'NOMBRE_INTRODUCCION',
        'ACTIVO'

    ];
}
