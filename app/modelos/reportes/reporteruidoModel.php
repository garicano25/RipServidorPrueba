<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteruidoModel extends Model
{
    protected $table = 'reporteruido';
	protected $fillable = [
		  'proyecto_id'
		, 'agente_id'
		, 'agente_nombre'
		, 'catactivo_id'
		, 'reporteruido_revision'
		, 'reporteruido_fecha'
		, 'reporteruido_instalacion'
		, 'reporteruido_catregion_activo'
		, 'reporteruido_catsubdireccion_activo'
		, 'reporteruido_catgerencia_activo'
		, 'reporteruido_catactivo_activo'
		, 'reporteruido_introduccion'
		, 'reporteruido_objetivogeneral'
		, 'reporteruido_objetivoespecifico'
		, 'reporteruido_metodologia_4_1'
		, 'reporteruido_metodologia_4_2'
		, 'reporteruido_ubicacioninstalacion'
		, 'reporteruido_ubicacionfoto'
		, 'reporteruido_procesoinstalacion'
		, 'reporteruido_actividadprincipal'
		, 'reporteruido_metodoevaluacion'
		, 'reporteruido_conclusion'
		, 'reporteruido_responsable1'
		, 'reporteruido_responsable1cargo'
		, 'reporteruido_responsable1documento'
		, 'reporteruido_responsable2'
		, 'reporteruido_responsable2cargo'
		, 'reporteruido_responsable2documento'
		, 'reporteruido_concluido'
		, 'reporteruido_concluidonombre'
		, 'reporteruido_concluidofecha'
		, 'reporteruido_cancelado'
		, 'reporteruido_canceladonombre'
		, 'reporteruido_canceladofecha'
		, 'reporteruido_canceladoobservacion'
	];
}
