<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportequimicosareacategoriaModel extends Model
{
    protected $table = 'reportequimicosareacategoria';
	protected $fillable = [
		  'reportequimicosarea_id'
		, 'reportequimicoscategoria_id'
		, 'reportequimicosareacategoria_poe'
		, 'reportequimicosareacategoria_total'
		, 'reportequimicosareacategoria_actividades'
	];
}
