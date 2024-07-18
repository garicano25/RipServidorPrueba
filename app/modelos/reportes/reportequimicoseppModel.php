<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportequimicoseppModel extends Model
{
    protected $table = 'reportequimicosepp';
	protected $fillable = [
		  'proyecto_id'
		, 'registro_id'
		, 'reportequimicosepp_partecuerpo'
		, 'reportequimicosepp_equipo'
	];
}
