<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteruidodosisnerModel extends Model
{
    protected $table = 'reporteruidodosisner';
	protected $fillable = [
		  'proyecto_id'
		, 'registro_id'
		, 'reporteruidoarea_id'
		, 'reporteruidocategoria_id'
		, 'reporteruidodosisner_punto'
		, 'reporteruidodosisner_dosis'
		, 'reporteruidodosisner_ner'
		, 'reporteruidodosisner_lmpe'
		, 'reporteruidodosisner_tmpe'
	];

	public function reporteruidoarea()
	{
		return $this->belongsTo(\App\modelos\reportes\reporteruidoareaModel::class);
	}


	public function reporteruidocategoria()
	{
		return $this->belongsTo(\App\modelos\reportes\reporteruidocategoriaModel::class);
	}
}
