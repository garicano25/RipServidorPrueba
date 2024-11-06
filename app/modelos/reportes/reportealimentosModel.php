<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportealimentosModel extends Model
{
    //
    protected $table = 'reportealimentos';
    protected $fillable = [
        'proyecto_id',
        'agente_id',
        'agente_nombre',
        'catactivo_id',
        'reportealimentos_revision',
        'reportealimentos_fecha',
        'reportealimentos_mes',
        'reportealimentos_instalacion',
        'reportealimentos_catregion_activo',
        'reportealimentos_catsubdireccion_activo',
        'reportealimentos_catgerencia_activo',
        'reportealimentos_catactivo_activo',
        'reportealimentos_introduccion',
        'reportealimentos_objetivogeneral',
        'reportealimentos_objetivoespecifico',
        'reportealimentos_metodologia_4_1',
        'reportealimentos_metodologia_4_2',
        'reportealimentos_metodologia_5_1',
        'reportealimentos_metodologia_5_2',
        'reportealimentos_ubicacioninstalacion',
        'reportealimentos_ubicacionfoto',
        'reportealimentos_procesoinstalacion',
        'reportealimentos_actividadprincipal',
        'reportealimentos_conclusion',
        'reportealimentos_responsable1',
        'reportealimentos_responsable1cargo',
        'reportealimentos_responsable1documento',
        'reportealimentos_responsable2',
        'reportealimentos_responsable2cargo',
        'reportealimentos_responsable2documento',
        'reportealimentos_concluido',
        'reportealimentos_concluidonombre',
        'reportealimentos_concluidofecha',
        'reportealimentos_cancelado',
        'reportealimentos_canceladonombre',
        'reportealimentos_canceladofecha',
        'reportealimentos_canceladoobservacion',
    ];
}
