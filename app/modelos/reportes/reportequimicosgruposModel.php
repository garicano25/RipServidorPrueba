<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportequimicosgruposModel extends Model
{
    protected $table = 'reportequimicosgrupos';
	protected $fillable = [
		  'proyecto_id'
		, 'registro_id'
		, 'proveedor_id'
		, 'catreportequimicospartidas_id'
		, 'reportequimicosproyecto_id'		
	];
}
