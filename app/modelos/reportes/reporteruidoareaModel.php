<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteruidoareaModel extends Model
{
    protected $table = 'reporteruidoarea';
	protected $fillable = [
		  'proyecto_id'
		, 'registro_id'
		, 'recsensorialarea_id'
		, 'reporteruidoarea_instalacion'
		, 'reporteruidoarea_nombre'
		, 'reporteruidoarea_numorden'
		, 'reporteruidoarea_proceso'
		, 'reporteruidoarea_porcientooperacion'
		, 'reporteruidoarea_tiporuido'
		, 'reporteruidoarea_evaluacion'
		, 'reporteruidoarea_LNI_1'
		, 'reporteruidoarea_LNI_2'
		, 'reporteruidoarea_LNI_3'
		, 'reporteruidoarea_LNI_4'
		, 'reporteruidoarea_LNI_5'
		, 'reporteruidoarea_LNI_6'
		, 'reporteruidoarea_LNI_7'
		, 'reporteruidoarea_LNI_8'
		, 'reporteruidoarea_LNI_9'
		, 'reporteruidoarea_LNI_10'
	];
}
