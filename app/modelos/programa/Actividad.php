<?php

namespace App\modelos\programa;

use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    //
    protected $table = 'actividad';
    protected $fillable = [
        'actividad_Orden',
        'actividad_Actividad',
        'actividad_Clasificacion',
        'actividad_Duracion'
    ];
    public function programa()
    {
        return $this->hasMany(\App\modelos\programa\Programa::class);
    }
}
