<?php

namespace App\modelos\catalogos;

use Illuminate\Database\Eloquent\Model;

class Cat_tipoacreditacionModel extends Model
{
    //
    protected $table = 'cat_tipoacreditacion';
    protected $fillable = [
		'catTipoAcreditacion_Nombre',
		'catTipoAcreditacion_Activo'
    ];

    public function acreditacion()
    {
    	return $this->hasMany(\App\modelos\catalogos\AcreditacionModel::class,'acreditacion_Tipo');
    }
}
