<?php

namespace App\modelos\eppcatalogo;

use Illuminate\Database\Eloquent\Model;

class catnormasinternacionalesModel extends Model
{
    protected $table = 'cat_normasinternacionalesepp';
    protected $primaryKey = 'ID_NORMAS_INTERNACIONALES';
    protected $fillable = [
        'ENTIDAD_INTERNACIONALES',
        'NOMBRE_NORMA_INTERNACIONALES',
        'DESCRIPCION_NORMA_INTERNACIONALES',
        'NOTAS_INTERNACIONALES_JSON',
        'APARTADO_INTERNACIONALES_JSON',
        'ACTIVO'

    ];
}
