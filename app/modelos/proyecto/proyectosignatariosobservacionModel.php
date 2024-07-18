<?php

namespace App\modelos\proyecto;

use Illuminate\Database\Eloquent\Model;

class proyectosignatariosobservacionModel extends Model
{
    protected $table = 'proyectosignatariosobservacion';
    protected $fillable = [
        'proyecto_id',
		'proveedor_id',
		'proyectosignatariosobservacion'
    ];
}
