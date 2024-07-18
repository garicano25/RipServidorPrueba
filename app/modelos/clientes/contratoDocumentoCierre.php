<?php

namespace App\modelos\clientes;

use Illuminate\Database\Eloquent\Model;

class contratoDocumentoCierre extends Model
{
    protected $primaryKey = 'ID_DOCUMENTO_CIERRE';
    protected $table = 'contratos_documentos_cierre';
    protected $fillable = [
        'CONTRATO_ID',
        'NOMBRE',
        'RUTA_DOCUMENTO',
        'JUSTIFICACION_CIERRE',
        'AUTORIZADO',
        'ELIMINADO',
   
    ];

}
