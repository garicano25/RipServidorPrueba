<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteaireareacategoriaModel extends Model
{
    protected $table = 'reporteaireareacategoria';
	protected $fillable = [
		  'reporteairearea_id'
		, 'reporteairecategoria_id'
		, 'reporteaireareacategoria_poe'
		, 'reporteaireareacategoria_total'
		, 'reporteaireareacategoria_actividades'
	];
}
