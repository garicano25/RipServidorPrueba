<?php

namespace App\modelos\proyecto;

use Illuminate\Database\Eloquent\Model;

class proyectoproveedoresquimicosModel extends Model
{
    protected $table = 'proyectoproveedoresquimicos';
    protected $fillable = [
        'proyecto_id',
		'proveedor_id',
        'catprueba_id',
		'proyectoproveedoresquimicos_nombre',        
		'proyectoproveedoresquimicos_puntos',
        'proyectoproveedoresquimicos_observacion'
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
