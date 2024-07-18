<?php

namespace App\modelos\proyecto;

use Illuminate\Database\Eloquent\Model;

class proyectoproveedoresModel extends Model
{
    protected $table = 'proyectoproveedores';
    protected $fillable = [
        'proyecto_id',
        'proveedor_id',
        'proyectoproveedores_tipoadicional',
        'catprueba_id',
        'proyectoproveedores_agente',
        'proyectoproveedores_puntos',
        'proyectoproveedores_observacion'
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
