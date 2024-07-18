<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteiluminacionModel extends Model
{
    protected $table = 'reporteiluminacion';
	protected $fillable = [
		  'proyecto_id'
		, 'agente_id'
		, 'agente_nombre'
		, 'catactivo_id'
		, 'reporteiluminacion_revision'
		, 'reporteiluminacion_fecha'
		, 'reporteiluminacion_instalacion'
		, 'reporteiluminacion_catregion_activo'
		, 'reporteiluminacion_catsubdireccion_activo'
		, 'reporteiluminacion_catgerencia_activo'
		, 'reporteiluminacion_catactivo_activo'
		, 'reporteiluminacion_introduccion'
		, 'reporteiluminacion_objetivogeneral'
		, 'reporteiluminacion_objetivoespecifico'
		, 'reporteiluminacion_metodologia_4_1'
		, 'reporteiluminacion_metodologia_4_2'
		, 'reporteiluminacion_metodologia_4_2_1'
		, 'reporteiluminacion_metodologia_4_2_2'
		, 'reporteiluminacion_metodologia_4_2_3'
		, 'reporteiluminacion_metodologia_4_2_4'
		, 'reporteiluminacion_ubicacioninstalacion'
		, 'reporteiluminacion_ubicacionfoto'
		, 'reporteiluminacion_procesoinstalacion'
		, 'reporteiluminacion_actividadprincipal'
		, 'reporteiluminacion_criterioseleccion'
		, 'reporteiluminacion_conclusion'
		, 'reporteiluminacion_responsable1'
		, 'reporteiluminacion_responsable1cargo'
		, 'reporteiluminacion_responsable1documento'
		, 'reporteiluminacion_responsable2'
		, 'reporteiluminacion_responsable2cargo'
		, 'reporteiluminacion_responsable2documento'
		, 'reporteiluminacion_concluido'
		, 'reporteiluminacion_concluidonombre'
		, 'reporteiluminacion_concluidofecha'
		, 'reporteiluminacion_cancelado'
		, 'reporteiluminacion_canceladonombre'
		, 'reporteiluminacion_canceladofecha'
		, 'reporteiluminacion_canceladoobservacion'
	];
}
