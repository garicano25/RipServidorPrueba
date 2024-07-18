<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteaguacategoriaModel extends Model
{
    protected $table = 'reporteaguacategoria';
	protected $fillable = [
		  'proyecto_id'
		, 'registro_id'
		, 'recsensorialcategoria_id'
		, 'reporteaguacategoria_nombre'
		, 'reporteaguacategoria_total'
	];
}
