<?php

namespace App\modelos\proyecto;

use Illuminate\Database\Eloquent\Model;

class proyectoordentrabajoModel extends Model
{
    protected $table = 'proyectoordentrabajo';
    protected $fillable = [
		'proyecto_id',
		'proyectoordentrabajo_folio',
		'proyectoordentrabajo_revision',
		'proyectoordentrabajo_autorizado',
		'proyectoordentrabajo_autorizadonombre',
		'proyectoordentrabajo_autorizadofecha',
		'proyectoordentrabajo_observacionot',
		'proyectoordentrabajo_cancelado',
		'proyectoordentrabajo_canceladonombre',
		'proyectoordentrabajo_canceladofecha',
		'proyectoordentrabajo_canceladoobservacion',
		'proyectoordentrabajo_observacionrevision'
    ];
    public function proyectoordentrabajodatos()
    {
        return $this->hasMany(\App\modelos\proyecto\proyectoordentrabajodatosModel::class,'proyectoordentrabajo_id');
    }
    public function proyecto()
    {
        return $this->belongsTo(\App\modelos\proyecto\proyectoModel::class);
    }
}
