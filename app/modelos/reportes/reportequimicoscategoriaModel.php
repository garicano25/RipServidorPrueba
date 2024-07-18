<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportequimicoscategoriaModel extends Model
{
    protected $table = 'reportequimicoscategoria';
	protected $fillable = [
		  'proyecto_id'
		, 'registro_id'
		, 'recsensorialcategoria_id'
		, 'reportequimicoscategoria_nombre'
		, 'reportequimicoscategoria_total'
	];
}
