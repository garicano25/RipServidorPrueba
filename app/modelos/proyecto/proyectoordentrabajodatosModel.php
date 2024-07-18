<?php

namespace App\modelos\proyecto;

use Illuminate\Database\Eloquent\Model;

class proyectoordentrabajodatosModel extends Model
{
    protected $table = 'proyectoordentrabajodatos';
    protected $fillable = [
		'proyectoordentrabajo_id',
		'proyectoordentrabajodatos_proveedorid',
		'proyectoordentrabajodatos_agenteid',
		'proyectoordentrabajodatos_agentenombre',
		'proyectoordentrabajodatos_agentepuntos',
		'proyectoordentrabajodatos_agentenormas',
		'proyectoordentrabajodatos_agenteobservacion'
    ];
    public function proyectoordentrabajo()
    {
        return $this->belongsTo(\App\modelos\proyecto\proyectoordentrabajoModel::class);
    }
    public function proveedor()
    {
        return $this->belongsTo(\App\modelos\catalogos\ProveedorModel::class,'proyectoordentrabajodatos_proveedorid');
    }
    public function programa()
    {
        return $this->hasMany(\App\modelos\programa\Programa::class,'proyectoordentrabajodatos_id');
    }
}
