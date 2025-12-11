<?php

namespace App\modelos\eppcatalogo;

use Illuminate\Database\Eloquent\Model;

class catentidadeseppModel extends Model
{
    protected $table = 'cat_entidadesepp';
    protected $primaryKey = 'ID_ENTIDAD_EPP';
    protected $fillable = [

        'NOMBRE_ENTIDAD',
        'ENTIDAD_DESCRIPCION',
        'ACTIVO',

    ];
}
