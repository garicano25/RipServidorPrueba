<?php

namespace App\modelos\eppcatalogo;

use Illuminate\Database\Eloquent\Model;

class catnormasnacionalesModel extends Model
{
    protected $table = 'cat_normasnacionalesepp';
    protected $primaryKey = 'ID_NORMAS_NACIONALES';
    protected $fillable = [
        'NOMBRE_NORMA_NACIONALES',
        'DESCRIPCION_NORMA_NACIONALES',
        'ENTIDAD_NACIONALES',
        'NOTAS_NACIONALES_JSON',
        'APARTADO_NACIONALES_JSON',
        'ACTIVO'

    ];
}
