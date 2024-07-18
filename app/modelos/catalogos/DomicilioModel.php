<?php

namespace App\modelos\catalogos;

use Illuminate\Database\Eloquent\Model;

class DomicilioModel extends Model
{
	protected $table = 'proveedordomicilio';
	protected $fillable = [
		'proveedor_id',
		'proveedorDomicilio_ciudad',
		'proveedorDomicilio_Direccion',
		'contactos_sucursal',
		// 'proveedorDomicilio_Contacto',
		// 'proveedorDomicilio_Cargo',
		// 'proveedorDomicilio_Telefono',
		'proveedorDomicilio_Eliminado'
	];
}
