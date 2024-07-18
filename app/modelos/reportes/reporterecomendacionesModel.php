<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporterecomendacionesModel extends Model
{
    protected $table = 'reporterecomendaciones';
	protected $fillable = [
		  'agente_id'
		, 'agente_nombre'
		, 'proyecto_id'
		, 'registro_id'
		, 'catactivo_id'
		, 'reporterecomendacionescatalogo_id'
		, 'reporterecomendaciones_tipo'
		, 'reporterecomendaciones_descripcion'
		, 'catalogo_id'
	];
}