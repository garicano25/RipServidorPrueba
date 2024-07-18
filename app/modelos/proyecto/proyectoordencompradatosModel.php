<?php

namespace App\modelos\proyecto;

use Illuminate\Database\Eloquent\Model;

class proyectoordencompradatosModel extends Model
{
    protected $table = 'proyectoordencompradatos';
	protected $fillable = [
		'proyectoordencompra_id',
		'proyectoordencompradatos_proveedorid',
		'proyectoordencompradatos_agenteid',
		'proyectoordencompradatos_agentenombre',
		'proyectoordencompradatos_agentepuntos',
		'proyectoordencompradatos_preciounitario',
		'proyectoordencompradatos_importetotal',
		'proyectoordencompradatos_agentenormas',
		'proyectoordencompradatos_agenteobservacion'
	];
}
