<?php

namespace App\modelos\reconocimientoergo;

use Illuminate\Database\Eloquent\Model;

class versionesrecoergoModel extends Model
{
    protected $table = 'versionesrecoergo';

    protected $primaryKey =
    'ID_VERSION_RECO_ERGO';

    protected $fillable = [
        'RECO_ID',
        'NUMERO_REVISION',
        'FINALIZADO',
        'FINALIZADO_POR',
        'FECHA_FINALIZADO',
        'CANCELADO',
        'CANCELADO_POR',
        'FECHA_CANCELADO',
        'MOTIVO_CANCELACION',
        'RUTA_DOCUMENTO'
    ];
}
