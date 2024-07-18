<?php

namespace App\modelos\catalogos;

use Illuminate\Database\Eloquent\Model;

class TablaPantillaClientesModel extends Model
{

    protected $primaryKey = 'ID_PLANTILLA_IMAGEN';
    protected $table = 'plantillas_imagenes_clientes';
    protected $fillable =
    [
        'NOMBRE_PLANTILLA',
        'RUTA_IMAGEN',
        'DESCRIPCION_PLANTILLA'
    ];
}
