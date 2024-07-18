<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteaguaareaModel extends Model
{
	protected $table = 'reporteaguaarea';
	protected $fillable = [
		  'proyecto_id'
		, 'registro_id'
		, 'recsensorialarea_id'
		, 'reporteaguaarea_instalacion'
		, 'reporteaguaarea_nombre'
		, 'reporteaguaarea_numorden'
		, 'reporteaguaarea_porcientooperacion'
	];
}
