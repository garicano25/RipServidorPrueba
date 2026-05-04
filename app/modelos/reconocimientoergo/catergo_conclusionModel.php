<?php

namespace App\modelos\reconocimientoergo;

use Illuminate\Database\Eloquent\Model;

class catergo_conclusionModel extends Model
{
    protected $primaryKey = 'ID_CONCLUSION';
    protected $table = 'catergo_conclusion';
    protected $fillable = [
        'NOMBRE_CONCLUSION',
        'ACTIVO'

    ];
}
