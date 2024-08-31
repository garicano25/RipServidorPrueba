<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteaireModel extends Model
{
	protected $table = 'reporteaire';
	protected $fillable = [
		'proyecto_id',
		'agente_id',
		'agente_nombre',
		'catactivo_id',
		'reporteaire_revision',
		'reporte_mes',
		'reporteaire_fecha',
		'reporteaire_instalacion',
		'reporteaire_catregion_activo',
		'reporteaire_catsubdireccion_activo',
		'reporteaire_catgerencia_activo',
		'reporteaire_catactivo_activo',
		'reporteaire_introduccion',
		'reporteaire_objetivogeneral',
		'reporteaire_objetivoespecifico',
		'reporteaire_metodologia_4_1',
		'reporteaire_metodologia_4_2',
		'reporteaire_ubicacioninstalacion',
		'reporteaire_ubicacionfoto',
		'reporteaire_procesoinstalacion',
		'reporteaire_actividadprincipal',
		'reporteaire_conclusion',
		'reporteaire_responsable1',
		'reporteaire_responsable1cargo',
		'reporteaire_responsable1documento',
		'reporteaire_responsable2',
		'reporteaire_responsable2cargo',
		'reporteaire_responsable2documento',
		'reporteaire_concluido',
		'reporteaire_concluidonombre',
		'reporteaire_concluidofecha',
		'reporteaire_cancelado',
		'reporteaire_canceladonombre',
		'reporteaire_canceladofecha',
		'reporteaire_canceladoobservacion'
	];
}
