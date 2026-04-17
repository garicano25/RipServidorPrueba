<?php

namespace App\modelos\reconocimientoergo;

use Illuminate\Database\Eloquent\Model;

class recoergocategoriasModel extends Model
{
    protected $primaryKey = 'ID_CATEGORIA_ERGO';
    protected $table = 'recoergocategorias';
    protected $fillable = [
        'RECO_ID',
        'NOMBRE_CATEGORIA_ERGO',
        'CAT_DEPARTAMENTO',
        'CAT_TIPOPUESTO',
        'DESCRIPCION_CATEGORIA_ERGO',
        'JSON_TURNOS',
        'PT_CATEGORIA',
        'ACTIVO'
    ];
}
