<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportehieloareaModel extends Model
{
    protected $table = 'reportehieloarea';
	protected $fillable = [
		  'proyecto_id'
		, 'registro_id'
		, 'recsensorialarea_id'
		, 'reportehieloarea_instalacion'
		, 'reportehieloarea_nombre'
		, 'reportehieloarea_numorden'
		, 'reportehieloarea_porcientooperacion'
	];
}
