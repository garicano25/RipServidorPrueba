<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class catpartecuerpoModel extends Model
{
    protected $table = 'catpartecuerpo';
	protected $fillable = [
		'catpartecuerpo_nombre',
		'catpartecuerpo_activo'
	];
}
