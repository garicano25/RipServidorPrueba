<?php

namespace App\modelos\proyecto;

use Illuminate\Database\Eloquent\Model;

class proyectoordenservicioadicionalModel extends Model
{
    protected $table = 'proyectoordenservicioadicional';
    protected $fillable = [
		  'proyecto_id'
		, 'proyectoordenservicio_id'
		, 'proyectoordenservicioadicional_nombre'
		, 'proyectoordenservicioadicional_pdf'
		, 'proyectoordenservicioadicional_eliminado'
    ];
}
