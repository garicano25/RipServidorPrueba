<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteruidoareacategoriaModel extends Model
{
    protected $table = 'reporteruidoareacategoria';
	protected $fillable = [
		  'reporteruidoarea_id'
		, 'reporteruidocategoria_id'
		, 'reporteruidoareacategoria_poe'
		, 'reporteruidoareacategoria_actividades'
	];
}