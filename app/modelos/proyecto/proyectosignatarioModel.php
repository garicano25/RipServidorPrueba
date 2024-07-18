<?php

namespace App\modelos\proyecto;

use Illuminate\Database\Eloquent\Model;

class proyectosignatarioModel extends Model
{
    protected $table = 'proyectosignatarios';
    protected $fillable = [
        'proyecto_id',
		'proyectosignatario_revision',
		'proyectosignatario_autorizado',
		'proyectosignatario_autorizadonombre',
		'proyectosignatario_autorizadofecha',
		'proyectosignatario_cancelado',
		'proyectosignatario_canceladonombre',
		'proyectosignatario_canceladofecha',
		'proyectosignatario_canceladoobservacion'
    ];
}
