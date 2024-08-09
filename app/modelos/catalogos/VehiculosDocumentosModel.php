<?php

namespace App\modelos\catalogos;

use Illuminate\Database\Eloquent\Model;

class VehiculosDocumentosModel extends Model
{
    protected $primaryKey = 'ID_VEHICULO_DOCUMENTO';
    protected $table = 'vehiculos_documentos';
    protected $fillable = [
        'VEHICULO_ID',
        'NOMBRE_DOCUMENTO_VEHICULOS',
        'RUTA_DOCUMENTO',
        'DOCUMENTO_TIPO_VEHICULOS',
        'FECHA_CARGA',
        'ACTIVO',


    ];
}
