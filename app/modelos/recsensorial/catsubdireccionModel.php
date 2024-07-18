<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class catsubdireccionModel extends Model
{
    protected $table = 'catsubdireccion';
	protected $fillable = [
		'catsubdireccion_siglas',
		'catsubdireccion_nombre',
		'catsubdireccion_activo'
	];
}
