<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportecategoriaModel extends Model
{
	protected $table = 'reportecategoria';
	protected $fillable = [
		  'proyecto_id'
		, 'registro_id'
		, 'recsensorialcategoria_id'
		, 'reportecategoria_nombre'
		, 'reportecategoria_total'
		, 'reportecategoria_orden'
		, 'reportecategoria_horas' //Iluminación
	];
}
