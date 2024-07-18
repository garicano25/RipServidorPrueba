<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteruidopuntonercategoriasModel extends Model
{
    protected $table = 'reporteruidopuntonercategorias';
	protected $fillable = [
		  'reporteruidopuntoner_id'
		, 'reporteruidocategoria_id'
		, 'reporteruidopuntonercategorias_total'
		, 'reporteruidopuntonercategorias_geo'
		, 'reporteruidopuntonercategorias_ficha'
		, 'reporteruidopuntonercategorias_nombre'
	];
}