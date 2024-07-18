<?php

namespace App\modelos\recsensorialquimicos;

use Illuminate\Database\Eloquent\Model;

class catestadofisicosustanciaModel extends Model
{
    protected $table = 'catestadofisicosustancia';
	protected $fillable = [
		'catestadofisicosustancia_estado',
		'catestadofisicosustancia_activo'
	];
}
