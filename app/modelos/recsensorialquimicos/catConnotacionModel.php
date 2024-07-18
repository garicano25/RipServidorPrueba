<?php

namespace App\modelos\recsensorialquimicos;

use Illuminate\Database\Eloquent\Model;

class catConnotacionModel extends Model
{
    protected $primaryKey = 'ID_CONNOTACION';
    protected $table = 'catConnotaciones';
    protected $fillable = [
        'ABREVIATURA',
        'DESCRIPCION',
        'ENTIDAD_ID',
        'ACTIVO'
    ];
}
