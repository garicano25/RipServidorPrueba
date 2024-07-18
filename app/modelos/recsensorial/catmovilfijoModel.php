<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class catmovilfijoModel extends Model
{
    protected $table = 'catmovilfijo';
    protected $fillable = [
		'catmovilfijo_nombre',
		'catmovilfijo_activo'
    ];
}
