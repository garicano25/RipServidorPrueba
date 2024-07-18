<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class recsensorialpruebasModel extends Model
{
    protected $table = 'recsensorialpruebas';
    protected $fillable = [
      'recsensorial_id',
      'catprueba_id',
      'cantidad'
      ];


    public function catprueba()
    {
        return $this->belongsTo(\App\modelos\catalogos\Cat_pruebaModel::class);
    }
}
