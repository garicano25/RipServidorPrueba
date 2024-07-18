<?php

namespace App\modelos\recsensorialquimicos;

use Illuminate\Database\Eloquent\Model;

class catunidadmedidasustaciaModel extends Model
{
    protected $table = 'catunidadmedidasustacia';
	protected $fillable = [
		'catunidadmedidasustacia_abreviacion',
		'catunidadmedidasustacia_descripcion',
		'catunidadmedidasustacia_activo'
	];
}
