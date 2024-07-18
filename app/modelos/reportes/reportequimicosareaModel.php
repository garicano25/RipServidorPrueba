<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportequimicosareaModel extends Model
{
    protected $table = 'reportequimicosarea';
	protected $fillable = [
		  'proyecto_id'
		, 'registro_id'
		, 'recsensorialarea_id'
		, 'reportequimicosarea_instalacion'
		, 'reportequimicosarea_nombre'
		, 'reportequimicosarea_numorden'
		, 'reportequimicosarea_porcientooperacion'
		, 'reportequimicosarea_maquinaria'
		, 'reportequimicosarea_contaminante'
		, 'reportequimicosarea_caracteristica'
	];
}
