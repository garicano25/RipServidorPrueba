<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporterecomendacionescategoriaModel extends Model
{
    protected $table = 'reporterecomendacionescategoria';
	protected $fillable = [
		 'proyecto_id'
		, 'registro_id'
        , 'reporterecomendaciones_descripcion'
		, 'reporterecomendacionescatalogo_id'
		, 'reporterecomendacionescategoria_id'
	];

}
