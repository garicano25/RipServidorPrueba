<?php

namespace App\modelos\eppcatalogo;

use Illuminate\Database\Eloquent\Model;

class documentoseppModel extends Model
{
    protected $table = 'documentos_epps';
    protected $primaryKey = 'ID_EPP_DOCUMENTO';
    protected $fillable = [

        'EPP_ID',
        'DOCUMENTO_TIPO',
        'NOMBRE_DOCUMENTO',
        'FOTO_DOCUMENTO',
        'EPP_PDF',
        'ACTIVO'


    ];
}
