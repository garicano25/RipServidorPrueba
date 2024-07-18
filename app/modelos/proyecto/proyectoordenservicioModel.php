<?php

namespace App\modelos\proyecto;

use Illuminate\Database\Eloquent\Model;

class proyectoordenservicioModel extends Model
{
    protected $table = 'proyectoordenservicio';
    protected $fillable = [
          'proyecto_id'
        , 'proyectoordenservicio_oficio'
        , 'proyectoordenservicio_numero'
        , 'proyectoordenservicio_cotizacion'
        , 'proyectoordenservicio_total'
        , 'proyectoordenservicio_contrato'
        , 'proyectoordenservicio_raf'
        , 'proyectoordenservicio_pedido'
        , 'proyectoordenservicio_observacion'
        , 'proyectoordenservicio_pdf'
        , 'proyectoordenservicio_validado'
        , 'proyectoordenservicio_fechavalidacion'
        , 'proyectoordenservicio_personavalidacion'
        , 'proyectoordenservicio_eliminado'
    ];

    //=============== RELACION A TABLAS ===================

    // public function proyecto()
    // {
    // 	return $this->belongsTo(\App\modelos\recsensorial\proyectoModel::class);
    // }

    //=============== RELACION A CATALOGOS ===================

    // public function catcontrato()
    // {
    //     return $this->belongsTo(\App\modelos\proyecto\catcontratoModel::class);
    // }
}
