<?php

namespace App\modelos\proyecto;

use Illuminate\Database\Eloquent\Model;

class proyectoequipoModel extends Model
{
    protected $table = 'proyectoequipos';
    protected $fillable = [
		'proyecto_id',
		'proyectoequipo_revision',
		'proyectoequipo_autorizado',
		'proyectoequipo_autorizadonombre',
		'proyectoequipo_autorizadofecha',
		'proyectoequipo_cancelado',
		'proyectoequipo_canceladonombre',
		'proyectoequipo_canceladofecha',
		'proyectoequipo_canceladoobservacion'
    ];
}
