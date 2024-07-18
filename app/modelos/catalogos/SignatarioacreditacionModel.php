<?php

namespace App\modelos\catalogos;

use Illuminate\Database\Eloquent\Model;

class SignatarioacreditacionModel extends Model
{
    //
   	protected $table = 'signatarioacreditacion';
    protected $fillable = [
		'signatario_id',
		// 'cat_areaacreditacion_id',
		// 'cat_prueba_id',
		'cat_signatarioestado_id',
        'signatarioAcreditacion_Alcance',
		'signatarioAcreditacion_Expedicion',
		'signatarioAcreditacion_Vigencia',
		'cat_signatariodisponibilidad_id',
		'signatarioAcreditacion_Eliminado'
    ];

    public function signatario()
    {
        return $this->belongsTo(\App\modelos\catalogos\SignatarioModel::class);
    }

    public function alcance()
    {
        return $this->belongsTo(\App\modelos\catalogos\AcreditacionalcanceModel::class, 'signatarioAcreditacion_Alcance');
    }

    public function cat_signatarioestado()
    {
        return $this->belongsTo(\App\modelos\catalogos\Cat_signatarioestadoModel::class);
    }

    public function cat_signatariodisponibilidad()
    {
        return $this->belongsTo(\App\modelos\catalogos\Cat_signatariodisponibilidadModel::class);
    }

    // public function cat_area()
    // {
    //     return $this->belongsTo(\App\modelos\catalogos\Cat_areaModel::class);
    // }

    // public function cat_prueba()
    // {
    //     return $this->belongsTo(\App\modelos\catalogos\Cat_pruebaModel::class);
    // }

    // public function acreditacion()
    // {
    //     return $this->belongsTo(\App\modelos\catalogos\AcreditacionModel::class,'cat_areaacreditacion_id');
    // }
}
