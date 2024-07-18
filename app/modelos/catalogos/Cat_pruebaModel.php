<?php

namespace App\modelos\catalogos;

use Illuminate\Database\Eloquent\Model;

class Cat_pruebaModel extends Model
{
    protected $table = 'cat_prueba';
    protected $primaryKey = 'id';
    protected $fillable = [
        'catPrueba_Tipo',
		'catPrueba_Nombre',
        'catPrueba_orden',
		'catPrueba_Activo'
    ];

    public function pruebanorma()
    {
        return $this->hasMany(\App\modelos\catalogos\Cat_pruebanormaModel::class, 'cat_prueba_id');
    }

    //=================================================

    public function acreditacionalcance()
    {
    	return $this->hasMany(\App\modelos\catalogos\AcreditacionalcanceModel::class,'prueba_id');
    }

    public function equipo()
    {
        return $this->hasMany(\App\modelos\catalogos\EquipoModel::class);
    }

    public function signatario()
    {
        return $this->hasMany(\App\modelos\catalogos\SignatarioModel::class);
    }

    public function servicio()
    {
        return $this->hasMany(\App\modelos\catalogos\ServicioModel::class);
    }
}
