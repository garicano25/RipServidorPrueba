<?php

namespace App\modelos\proyecto;

use Illuminate\Database\Eloquent\Model;

class estructuraProyectosModel extends Model
{
    protected $primaryKey = 'ID_ESTRUCTURA_PROYECTO';
    protected $table = 'estructuraProyectos';
    protected $fillable = [
        'PROYECTO_ID',
        'ETIQUETA_ID',
        'OPCION_ID',
        'NIVEL'
    ];
}
