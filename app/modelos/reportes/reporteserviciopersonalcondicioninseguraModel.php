<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteserviciopersonalcondicioninseguraModel extends Model
{
    protected $table = 'reporteserviciopersonalcondicioninsegura';
    protected $fillable = [
          'proyecto_id'
        , 'reportearea_id'
        , 'reporteserviciopersonalcondicioninsegura_actividad'
        , 'reporteserviciopersonalcondicioninsegura_rutinario'
        , 'reporteserviciopersonalcondicioninsegura_descripcion'
        , 'reporteserviciopersonalcondicioninsegura_clasificacion'
        , 'reporteserviciopersonalcondicioninsegura_efectos'
        , 'reporteserviciopersonalcondicioninsegura_fuente'
        , 'reporteserviciopersonalcondicioninsegura_medio'
        , 'reporteserviciopersonalcondicioninsegura_probabilidad'
        , 'reporteserviciopersonalcondicioninsegura_exposicion'
        , 'reporteserviciopersonalcondicioninsegura_consecuencia'
        , 'reporteserviciopersonalcondicioninsegura_foto1'
        , 'reporteserviciopersonalcondicioninsegura_foto2'
    ];


    public function reportearea()
    {
        return $this->belongsTo(\App\modelos\reportes\reporteareaModel::class);
    }
}
