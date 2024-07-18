<?php

namespace App\modelos\recsensorialquimicos;

use Illuminate\Database\Eloquent\Model;

class catUnidadMedidaModel extends Model
{
    protected $primaryKey = 'ID_UNIDAD_MEDIDA';
    protected $table = 'cat_UnidadMedida';
    protected $fillable = [
        'DESCRIPCION',
        'ABREVIATURA',
        'ACTIVO'
    ];
}
