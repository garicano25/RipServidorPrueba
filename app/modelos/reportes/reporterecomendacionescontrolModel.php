<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporterecomendacionescontrolModel extends Model
{
    protected $table = 'reporterecomendacionescontrol';
	protected $fillable = [
		 'proyecto_id'
		, 'registro_id'
        , 'reporterecomendaciones_descripcion'
		, 'reporterecomendacionescatalogo_id'
	];
}
