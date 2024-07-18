<?php

namespace App\modelos\catalogos;

use Illuminate\Database\Eloquent\Model;

class CursosDocumentosValidacionModel extends Model
{
    protected $primaryKey = 'ID_DOCUMENTO_CURSO';
    protected $table = 'cursos_documentos_validacion';
    protected $fillable = [
        'CURSO_ID',
        'NOMBRE_DOCUMENTO',
        'RUTA_PDF',
        'ELIMINADO'
    ];

}
