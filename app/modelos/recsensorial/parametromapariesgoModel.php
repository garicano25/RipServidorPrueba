<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class parametromapariesgoModel extends Model
{
    protected $table = 'parametromapariesgo';
	protected $fillable = [
		'recsensorial_id',
		'parametromapariesgo_tipo1',
		'parametromapariesgo_tipo2'
	];
}
