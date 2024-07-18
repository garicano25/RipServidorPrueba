<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteairecategoriaModel extends Model
{
    protected $table = 'reporteairecategoria';
	protected $fillable = [
		  'proyecto_id'
		, 'registro_id'
		, 'recsensorialcategoria_id'
		, 'reporteairecategoria_nombre'
		, 'reporteairecategoria_total'
	];
}
