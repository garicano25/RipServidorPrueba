<?php

namespace App\modelos\catalogos;

use Illuminate\Database\Eloquent\Model;

class Cat_areaModel extends Model
{
    //
    protected $table = 'cat_area';
    protected $fillable = [
		'catArea_Nombre',
		'catArea_Activo'
    ];

    public function acreditacion()
    {
    	return $this->hasMany(\App\modelos\catalogos\AcreditacionModel::class);
    }

    // public function equipo()
    // {
    //     return $this->hasMany(\App\modelos\catalogos\EquipoModel::class,'equipoacreditacion_id');
    // }

    public function signatario()
    {
        return $this->hasMany(\App\modelos\catalogos\SignatarioModel::class);
    }

    public function servicio()
    {
        return $this->hasMany(\App\modelos\catalogos\ServicioModel::class);
    }
}
