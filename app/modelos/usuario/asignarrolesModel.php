<?php

namespace App\modelos\usuario;

use Illuminate\Database\Eloquent\Model;

class asignarrolesModel extends Model
{
    protected $table = 'asignar_roles';
    protected $fillable = [
        'usuario_id',
		'rol_id',
    ];


    public function rol()
    {
    	return $this->hasMany(\App\modelos\usuario\rolModel::class);
    }
}