<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteruidoequipoauditivoModel extends Model
{
    protected $table = 'reporteruidoequipoauditivo';
	protected $fillable = [
		  'proyecto_id'
		, 'registro_id'
		, 'reporteruidoequipoauditivo_tipo'
		, 'reporteruidoequipoauditivo_marca'
		, 'reporteruidoequipoauditivo_modelo'
		, 'reporteruidoequipoauditivo_NRR'
	];
}
