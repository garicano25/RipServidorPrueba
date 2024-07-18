<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class catdepartamentoModel extends Model
{
    protected $table = 'catdepartamento';
    protected $fillable = [
		'catdepartamento_nombre',
		'catdepartamento_activo'
    ];
}
