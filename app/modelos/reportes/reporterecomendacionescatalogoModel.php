<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporterecomendacionescatalogoModel extends Model
{
    protected $table = 'reporterecomendacionescatalogo';
	protected $fillable = [
		  'agente_id'
		, 'agente_nombre'
		, 'reporterecomendacionescatalogo_tipo'
		, 'reporterecomendacionescatalogo_descripcion'
		, 'reporterecomendacionescatalogo_activo'
	];
}
