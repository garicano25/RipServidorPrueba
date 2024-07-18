<?php

namespace App\modelos\recsensorialquimicos;

use Illuminate\Database\Eloquent\Model;

class catEntidadesModel extends Model
{
    protected $primaryKey = 'ID_ENTIDAD';
    protected $table = 'catEntidades';
    protected $fillable = [
        'DESCRIPCION',
        'ENTIDAD',
        'ACTIVO'
    ];
}
