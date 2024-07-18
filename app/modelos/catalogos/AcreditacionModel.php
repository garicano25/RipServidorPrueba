<?php

namespace App\modelos\catalogos;

use Illuminate\Database\Eloquent\Model;
// use Carbon\Carbon;
// use DateTime;

class AcreditacionModel extends Model
{
    //
    protected $table = 'acreditacion';
    protected $fillable = [
		'proveedor_id',
		// 'acreditacion_Servicio',
		// 'acreditacion_EmpresaSub',
		'acreditacion_Tipo',
		'acreditacion_Entidad',
		'acreditacion_Numero',
		'cat_area_id',
		'acreditacion_Expedicion',
		'acreditacion_Vigencia',
		'acreditacion_SoportePDF',
		'acreditacion_FechaAlta',
		'acreditacion_FechaModificacion',
		'acreditacion_Eliminado'
    ];

    //=======================================================

    public function proveedor()
    {
        return $this->belongsTo(\App\modelos\catalogos\ProveedorModel::class);
    }

    // public function cat_acreditacionservicio()
    // {
    //     return $this->belongsTo(\App\modelos\catalogos\Cat_servicioacreditacionModel::class,'acreditacion_Servicio');
    // }

    public function cat_tipoacreditacion()
    {
        return $this->belongsTo(\App\modelos\catalogos\Cat_tipoacreditacionModel::class, 'acreditacion_Tipo');
    }

    public function cat_area()
    {
        return $this->belongsTo(\App\modelos\catalogos\Cat_areaModel::class);
    }

    public function acreditacionalcance()
    {
    	return $this->hasMany(\App\modelos\catalogos\AcreditacionalcanceModel::class, 'acreditacion_id');
    }
}
