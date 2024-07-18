<?php

namespace App\modelos\catalogos;

use Illuminate\Database\Eloquent\Model;

class Cat_signatariodisponibilidadModel extends Model
{
    //
    protected $table = 'cat_signatariodisponibilidad';
    protected $fillable = [
		'cat_Signatariodisponibilidad_Nombre',
		'cat_Signatariodisponibilidad_Activo'
    ];

    public function signatarioacreditacion()
    {
        return $this->hasMany(\App\modelos\catalogos\SignatarioacreditacionModel::class);
    }
}
