<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteruidoequipoauditivocategoriasModel extends Model
{
    protected $table = 'reporteruidoequipoauditivocategorias';
	protected $fillable = [
		  'reporteruidoequipoauditivo_id'
		, 'reporteruidocategoria_id'
	];
}
