<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reportehielocategoriaModel extends Model
{
    protected $table = 'reportehielocategoria';
	protected $fillable = [
		  'proyecto_id'
		, 'registro_id'
		, 'recsensorialcategoria_id'
		, 'reportehielocategoria_nombre'
		, 'reportehielocategoria_total'
	];
}
