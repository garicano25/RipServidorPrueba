<?php

namespace App\modelos\programa;

use Illuminate\Database\Eloquent\Model;

class Programa extends Model
{
    //
    protected $table = 'programa';
    protected $fillable = [
        'proyecto_id',
        'proyectoordentrabajo_id',
        'proyectoordentrabajodatos_id',
        'actividad_id',
        'programa_Actividad',
        'programa_Clasificacion',
        'programa_DuracionPrograma',
        'programa_InicioPrograma',
        'programa_FinPrograma',
        'programa_InicioReal',
        'programa_FinReal',
        'programa_DuracionReal',
        'programa_Responsable',
        'programa_Autorizado',
        'programa_Estatus',
        'programa_Version',
        'programa_Comentario',
        'programa_Revisa',
        'programa_Autoriza',
        'programa_Evidencia'
    ];
    public function actividad()
    {
        return $this->belongsTo(\App\modelos\programa\Actividad::class);
    }
    public function proyecto()
    {
        return $this->belongsTo(\App\modelos\proyecto\proyectoModel::class);
    }
    public function proyectoordentrabajodatos()
    {
        return $this->belongsTo(\App\modelos\proyecto\proyectoordentrabajodatosModel::class);
    }
    public function proyectoordentrabajo()
    {
        return $this->belongsTo(\App\modelos\proyecto\proyectoordentrabajoModel::class);
    }
}
