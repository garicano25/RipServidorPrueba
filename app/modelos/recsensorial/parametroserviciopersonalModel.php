<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class parametroserviciopersonalModel extends Model
{
	protected $table = 'parametroserviciopersonal';
	protected $fillable = [
		'recsensorial_id',
		'parametroserviciopersonal_puntos'
	];
}
