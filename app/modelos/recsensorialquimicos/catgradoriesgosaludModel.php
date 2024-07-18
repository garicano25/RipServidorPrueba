<?php

namespace App\modelos\recsensorialquimicos;

use Illuminate\Database\Eloquent\Model;

class catgradoriesgosaludModel extends Model
{
	protected $table = 'catgradoriesgosalud';
	protected $fillable = [
		'catgradoriesgosalud_clasificacion',
		'catgradoriesgosalud_viaingresooral',
		'catgradoriesgosalud_viaingresopiel',
		'catgradoriesgosalud_viaingresoinhalacion',
		'catgradoriesgosalud_ponderacion',
		'catgradoriesgosalud_activo',
		'CLASIFICACION_RIESGO_GRADO'
	];
}
