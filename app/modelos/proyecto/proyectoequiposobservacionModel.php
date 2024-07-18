<?php

namespace App\modelos\proyecto;

use Illuminate\Database\Eloquent\Model;

class proyectoequiposobservacionModel extends Model
{
    protected $table = 'proyectoequiposobservacion';
    protected $fillable = [
        'proyecto_id',
		'proveedor_id',
		'proyectoequiposobservacion'
    ];
}
