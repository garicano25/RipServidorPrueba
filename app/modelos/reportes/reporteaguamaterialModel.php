<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteaguamaterialModel extends Model
{
    protected $table = 'reporteaguamaterial';
	protected $fillable = [
		  'proyecto_id'
		, 'registro_id'
		, 'reporteaguamaterial_nombre'
	];
}
