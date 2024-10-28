<?php

namespace App\modelos\catalogos;

use Illuminate\Database\Eloquent\Model;

class centroInformacionModel extends Model
{
    //
    protected $primaryKey = 'ID_CENTRO_INFORMACION';
    protected $table = 'centroInformacion';
    protected $fillable = [
        'CLASIFICACION',
        'TITULO',
        'DESCRIPCION',
        'RUTA_DOCUMENTO',
        'RUTA_LINK',
    ];
}
