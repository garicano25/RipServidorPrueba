<?php

namespace App\modelos\catalogos;

use Illuminate\Database\Eloquent\Model;

class Cat_signatarioestadoModel extends Model
{
    //
    protected $table = 'cat_signatarioestado';
    protected $fillable = [
		'cat_Signatarioestado_Nombre',
		'cat_Signatarioestado_Activo'
    ];

    public function signatario()
    {
        return $this->hasMany(\App\modelos\catalogos\SignatarioModel::class);
    }
}
