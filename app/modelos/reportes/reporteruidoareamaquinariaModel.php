<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteruidoareamaquinariaModel extends Model
{
    protected $table = 'reporteruidoareamaquinaria';
	protected $fillable = [
		  'reporteruidoarea_id'
		, 'reporteruidoareamaquinaria_poe'
		, 'reporteruidoareamaquinaria_nombre'
		, 'reporteruidoareamaquinaria_cantidad'
	];
}
