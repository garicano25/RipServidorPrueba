<?php

namespace App\modelos\proyecto;

use Illuminate\Database\Eloquent\Model;

class proyectoordencompraModel extends Model
{
	protected $table = 'proyectoordencompra';
	protected $fillable = [
		  'proyecto_id'
		, 'proveedor_id'
		, 'servicio_id'
		, 'proyectoordencompra_folio'
		, 'proyectoordencompra_revision'
		, 'proyectoordencompra_tipolista'
		, 'proyectoordencompra_revisado'
		, 'proyectoordencompra_revisadonombre'
		, 'proyectoordencompra_revisadofecha'
		, 'proyectoordencompra_autorizado'
		, 'proyectoordencompra_autorizadonombre'
		, 'proyectoordencompra_autorizadofecha'
		, 'proyectoordencompra_observacionoc'
		, 'proyectoordencompra_cancelado'
		, 'proyectoordencompra_canceladonombre'
		, 'proyectoordencompra_canceladofecha'
		, 'proyectoordencompra_canceladoobservacion'
		, 'proyectoordencompra_observacionrevision'
		, 'proyectoordencompra_facturado'
		, 'proyectoordencompra_facturadonombre'
		, 'proyectoordencompra_facturadofecha'
		, 'proyectoordencompra_facturadomonto'
		, 'proyectoordencompra_facturadopdf'
	];
}
