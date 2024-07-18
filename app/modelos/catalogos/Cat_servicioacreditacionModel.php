<?php

namespace App\modelos\catalogos;

use Illuminate\Database\Eloquent\Model;

class Cat_servicioacreditacionModel extends Model
{
    //
    protected $table = 'cat_servicioacreditacion';
    protected $fillable = [
		'catServicioAcreditacion_Nombre',
		'catServicioAcreditacion_Activo'
    ];

    public function acreditacion()
    {
    	return $this->hasMany(\App\modelos\catalogos\AcreditacionModel::class,'acreditacion_Servicio');
    }
}
