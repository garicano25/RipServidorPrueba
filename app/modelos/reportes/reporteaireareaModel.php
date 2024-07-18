<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteaireareaModel extends Model
{
    protected $table = 'reporteairearea';
	protected $fillable = [
		  'proyecto_id'
		, 'registro_id'
		, 'recsensorialarea_id'
		, 'reporteairearea_instalacion'
		, 'reporteairearea_nombre'
		, 'reporteairearea_numorden'
		, 'reporteairearea_porcientooperacion'
		, 'reporteairearea_ventilacionsistema'
		, 'reporteairearea_ventilacioncaracteristica'
		, 'reporteairearea_ventilacioncantidad'
	];
}
