<?php

namespace App\modelos\catalogos;

use Illuminate\Database\Eloquent\Model;

class ServiciopreciosModel extends Model
{
    protected $table = 'servicioprecios';
    protected $fillable = [
        'servicio_id',
		'agente_id',
		'agente_nombre',
		'agente_preciounitario',
        'ACTIVO_PARTIDAPROVEEDOR'
    ];
}
