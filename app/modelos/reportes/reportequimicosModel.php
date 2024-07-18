<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportequimicosModel extends Model
{
    protected $table = 'reportequimicos';
	protected $fillable = [
		  'proyecto_id'
		, 'agente_id'
		, 'agente_nombre'
		, 'catactivo_id'
		, 'reportequimicos_revision'
		, 'reportequimicos_fecha'
		, 'reportequimicos_instalacion'
		, 'reportequimicos_catregion_activo'
		, 'reportequimicos_catsubdireccion_activo'
		, 'reportequimicos_catgerencia_activo'
		, 'reportequimicos_catactivo_activo'
		, 'reportequimicos_introduccion'
		, 'reportequimicos_objetivogeneral'
		, 'reportequimicos_objetivoespecifico'
		, 'reportequimicos_metodologia_4_1'
		, 'reportequimicos_metodologia_4_2'
		, 'reportequimicos_ubicacioninstalacion'
		, 'reportequimicos_ubicacionfoto'
		, 'reportequimicos_procesoinstalacion'
		, 'reportequimicos_actividadprincipal'
		, 'reportequimicos_conclusion'
		, 'reportequimicos_responsable1'
		, 'reportequimicos_responsable1cargo'
		, 'reportequimicos_responsable1documento'
		, 'reportequimicos_responsable2'
		, 'reportequimicos_responsable2cargo'
		, 'reportequimicos_responsable2documento'
		, 'reportequimicos_concluido'
		, 'reportequimicos_concluidonombre'
		, 'reportequimicos_concluidofecha'
		, 'reportequimicos_cancelado'
		, 'reportequimicos_canceladonombre'
		, 'reportequimicos_canceladofecha'
		, 'reportequimicos_canceladoobservacion'
	];


	public function proyecto()
    {
        return $this->belongsTo(\App\modelos\proyecto\proyectoModel::class);
    }
}
