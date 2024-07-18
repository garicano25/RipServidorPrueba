<?php

namespace App\modelos\catalogos;

use Illuminate\Database\Eloquent\Model;

class EquiposDocumentosModel extends Model
{

    protected $primaryKey = 'ID_EQUIPO_DOCUMENTO';
    protected $table = 'equipos_documentos';
    protected $fillable = [
        'EQUIPO_ID',
        'NOMBRE_DOCUMENTO',
        'RUTA_DOCUMENTO',
        'DOCUMENTO_TIPO',
        'FECHA_CARGA',
        'ACTIVO',


    ];
}
