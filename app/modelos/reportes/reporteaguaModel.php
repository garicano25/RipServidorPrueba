<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteaguaModel extends Model
{
	protected $table = 'reporteagua';
	protected $fillable = [
		  'proyecto_id'
		, 'agente_id'
		, 'agente_nombre'
		, 'catactivo_id'
		, 'reporteagua_revision'
		,'reporte_mes'
		, 'reporteagua_fecha'
		, 'reporteagua_instalacion'
		, 'reporteagua_catregion_activo'
		, 'reporteagua_catsubdireccion_activo'
		, 'reporteagua_catgerencia_activo'
		, 'reporteagua_catactivo_activo'
		, 'reporteagua_introduccion'
		, 'reporteagua_introduccion2'
		, 'reporteagua_objetivogeneral'
		, 'reporteagua_objetivoespecifico'
		, 'reporteagua_objetivoespecifico2'
		, 'reporteagua_metodologia_4_1'
		, 'reporteagua_metodologia_4_2'
		, 'reporteagua_metodologia_4_3'
		, 'reporteagua_metodologia_4_32'
		, 'reporteagua_metodologia_4_3_1'
		, 'reporteagua_ubicacioninstalacion'
		, 'reporteagua_ubicacionfoto'
		, 'reporteagua_procesoinstalacion'
		, 'reporteagua_actividadprincipal'
		, 'reporteagua_procesoelaboracion'
		, 'reporteagua_conclusion'
		, 'reporteagua_conclusion2'
		, 'reporteagua_responsable1'
		, 'reporteagua_responsable1cargo'
		, 'reporteagua_responsable1documento'
		, 'reporteagua_responsable2'
		, 'reporteagua_responsable2cargo'
		, 'reporteagua_responsable2documento'
		, 'reporteagua_concluido'
		, 'reporteagua_concluidonombre'
		, 'reporteagua_concluidofecha'
		, 'reporteagua_cancelado'
		, 'reporteagua_canceladonombre'
		, 'reporteagua_canceladofecha'
		, 'reporteagua_canceladoobservacion'
	];
}
