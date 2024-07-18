<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class parametroruidoequiposModel extends Model
{
    protected $table = 'parametroruidoequipos';
    protected $fillable = [
          'recsensorial_id'
        , 'proveedor_id'
        , 'equipo_id'
    ];
}