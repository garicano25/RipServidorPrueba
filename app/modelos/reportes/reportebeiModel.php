<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportebeiModel extends Model
{
    //
    protected $table = 'reportebei';
    protected $fillable = [
        'proyecto_id',
        'agente_id',
        'agente_nombre',
        'catactivo_id',
        'reportebei_revision',
        'reportebei_fecha',
        'reportebei_mes',
        'reportebei_instalacion',
        'reportebei_catregion_activo',
        'reportebei_catsubdireccion_activo',
        'reportebei_catgerencia_activo',
        'reportebei_catactivo_activo',
        'reportebei_introduccion',
        'reportebei_objetivogeneral',
        'reportebei_objetivoespecifico',
        'reportebei_metodologia_4_1',
        'reportebei_metodologia_4_2',
        'reportebei_metodologia_4_2_1',
        'reportebei_metodologia_4_2_2',
        'reportebei_metodologia_4_2_3',
        'reportebei_metodologia_4_2_4',
        'reportebei_ubicacioninstalacion',
        'reportebei_ubicacionfoto',
        'reportebei_procesoinstalacion',
        'reportebei_actividadprincipal',
        'reportebei_criterioseleccion',
        'reportebei_conclusion',
        'reportebei_responsable1',
        'reportebei_responsable1cargo',
        'reportebei_responsable1documento',
        'reportebei_responsable2',
        'reportebei_responsable2cargo',
        'reportebei_responsable2documento',
        'reportebei_concluido',
        'reportebei_concluidonombre',
        'reportebei_concluidofecha',
        'reportebei_cancelado',
        'reportebei_canceladonombre',
        'reportebei_canceladofecha',
        'reportebei_canceladoobservacion',
        'reportebei_excel'
    ];
}

