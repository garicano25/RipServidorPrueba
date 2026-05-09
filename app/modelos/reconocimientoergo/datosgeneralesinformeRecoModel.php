<?php

namespace App\modelos\reconocimientoergo;

use Illuminate\Database\Eloquent\Model;

class datosgeneralesinformeRecoModel extends Model
{
    protected $table = 'datosgeneralesinformeReco';
    protected $primaryKey = 'ID_ERGO_INFORME';
    protected $fillable = [

        'RECO_ID',
        'SELECT_INTRODUCCION',
        'INFORME_INTRODUCCION',
        'INFORME_OBJETIVOGENERALES',
        'INFORME_OBJETIVOSESPECIFICOS',
        'INFORME_UBICACIONINSTALACION',
        'RUTA_IMAGEN_UBICACION',
        'INFORME_PROCESOINSTALACION',
        'INFORME_ACTIVIDADPRINCIPAL',
        'SELECT_CONCLUSION',
        'INFORME_CONCLUSION',
        'INFORME_RESPONSABLE1',
        'INFORME_RESPONSABLE1CARGO',
        'INFORME_RESPONSABLE1DOCUMENTO',
        'INFORME_RESPONSABLE2',
        'INFORME_RESPONSABLE2CARGO',
        'INFORME_RESPONSABLE2DOCUMENTO',

    ];
}
