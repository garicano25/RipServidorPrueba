<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteruidocategoriaModel extends Model
{
    protected $table = 'reporteruidocategoria';
	protected $fillable = [
		  'proyecto_id'
		, 'registro_id'
		, 'recsensorialcategoria_id'
		, 'reporteruidocategoria_nombre'
		, 'reporteruidocategoria_total'
	];
}
