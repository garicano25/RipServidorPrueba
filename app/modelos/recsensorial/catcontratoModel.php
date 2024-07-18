<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class catcontratoModel extends Model
{
    protected $table = 'catcontrato';
    protected $fillable = [
		'catcontrato_numero',
		'catcontrato_empresa',
		'catcontrato_fechainicio',
		'catcontrato_fechafin',
		'catcontrato_montomxn',
		'catcontrato_montousd',
		'catcontrato_activo'
    ];

    //============ RELACION CON TABLAS ==========

    public function recsensorial()
    {
    	return $this->hasMany(\App\modelos\recsensorial\recsensorialModel::class);
    }
}
