<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class catConclusionesModel extends Model
{
    protected $primaryKey = 'ID_CATCONCLUSION';
    protected $table = 'catConclusiones';
    protected $fillable = [
        'NOMBRE',
        'DESCRIPCION',
        'ACTIVO'
    ];
}
