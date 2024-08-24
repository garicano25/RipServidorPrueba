<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteruidopuntonerModel extends Model
{
	protected $table = 'reporteruidopuntoner';
	protected $fillable = [
		'proyecto_id',
		'registro_id',
		'reporteruidoarea_id',
		'reporteruidopuntoner_punto',
		'reporteruidopuntoner_ubicacion',
		'reporteruidopuntoner_identificacion',
		'reporteruidopuntoner_tmpe',
		'reporteruidopuntoner_ner',
		'reporteruidopuntoner_lmpe',
		'reporteruidopuntoner_RdB',
		'reporteruidopuntoner_NRE'
	];

	public function reporteruidoarea()
	{
		return $this->belongsTo(\App\modelos\reportes\reporteruidoareaModel::class);
	}
}
