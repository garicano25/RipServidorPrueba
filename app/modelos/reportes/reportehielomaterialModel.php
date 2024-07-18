<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportehielomaterialModel extends Model
{
    protected $table = 'reportehielomaterial';
	protected $fillable = [
		  'proyecto_id'
		, 'registro_id'
		, 'reportehielomaterial_nombre'
	];
}
