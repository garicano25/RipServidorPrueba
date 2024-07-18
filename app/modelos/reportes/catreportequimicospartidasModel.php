<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class catreportequimicospartidasModel extends Model
{
    protected $table = 'catreportequimicospartidas';
	protected $fillable = [
		  'catreportequimicospartidas_numero'
		, 'catreportequimicospartidas_descripcion'
		, 'catreportequimicospartidas_activo'
	];
}
