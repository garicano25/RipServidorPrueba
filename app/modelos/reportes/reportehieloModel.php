<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportehieloModel extends Model
{
    protected $table = 'reportehielo';
	protected $fillable = [
		  'proyecto_id'
		, 'agente_id'
		, 'agente_nombre'
		, 'catactivo_id'
		, 'reportehielo_revision'
		,'reporte_mes'
		, 'reportehielo_fecha'
		, 'reportehielo_instalacion'
		, 'reportehielo_catregion_activo'
		, 'reportehielo_catsubdireccion_activo'
		, 'reportehielo_catgerencia_activo'
		, 'reportehielo_catactivo_activo'
		, 'reportehielo_introduccion'
		, 'reportehielo_introduccion2'
		, 'reportehielo_objetivogeneral'
		, 'reportehielo_objetivoespecifico'
		, 'reportehielo_objetivoespecifico2'
		, 'reportehielo_metodologia_4_1'
		, 'reportehielo_metodologia_4_12'
		, 'reportehielo_metodologia_4_2'
		, 'reportehielo_metodologia_4_22'
		, 'reportehielo_metodologia_4_3'
		, 'reportehielo_metodologia_4_32'
		, 'reportehielo_ubicacioninstalacion'
		, 'reportehielo_ubicacionfoto'
		, 'reportehielo_procesoinstalacion'
		, 'reportehielo_procesoelaboracion'
		, 'reportehielo_conclusion'
		, 'reportehielo_conclusion2'
		, 'reportehielo_responsable1'
		, 'reportehielo_responsable1cargo'
		, 'reportehielo_responsable1documento'
		, 'reportehielo_responsable2'
		, 'reportehielo_responsable2cargo'
		, 'reportehielo_responsable2documento'
		, 'reportehielo_concluido'
		, 'reportehielo_concluidonombre'
		, 'reportehielo_concluidofecha'
		, 'reportehielo_cancelado'
		, 'reportehielo_canceladonombre'
		, 'reportehielo_canceladofecha'
		, 'reportehielo_canceladoobservacion'
	];
}
