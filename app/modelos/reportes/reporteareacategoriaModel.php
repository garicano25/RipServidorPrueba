<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteareacategoriaModel extends Model
{
    protected $table = 'reporteareacategoria';
	protected $fillable = [
		  'reportearea_id'
		, 'reportecategoria_id'
		, 'reporteareacategoria_total'
		, 'reporteareacategoria_geh'
		, 'reporteareacategoria_actividades'
	];
}
