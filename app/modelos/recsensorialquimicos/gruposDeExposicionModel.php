<?php

namespace App\modelos\recsensorialquimicos;

use Illuminate\Database\Eloquent\Model;

class gruposDeExposicionModel extends Model
{

    protected $primaryKey = 'ID_GRUPO_EXPOSICION';
    protected $table = 'grupos_de_exposicion';
    protected $fillable = [
        'OPCION',
        'CLASIFICACION',
        'RECSENSORIAL_ID',
        'RELACION_AREA_CAT_ID',
        'AREA_ID',
        'CATEGORIA_ID',
        'RELACION_HOJA_SUS_ID',
        'POE',
        'PPT_VIEJO',
        'CT_VIEJO',
        'PUNTOS_VIEJO',
        'PPT_NUEVO',
        'CT_NUEVO',
        'PUNTOS_NUEVO',
        'JUSTIFICACION',
    ];
}
