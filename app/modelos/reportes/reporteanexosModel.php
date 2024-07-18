<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteanexosModel extends Model
{
    protected $table = 'reporteanexos';
	protected $fillable = [
		  'proyecto_id'
		, 'agente_id'
		, 'agente_nombre'
		, 'registro_id'
		, 'reporteanexos_tipo'
		, 'reporteanexos_anexonombre'
		, 'reporteanexos_rutaanexo'
	];
}
