<?php

namespace App\modelos\proyecto;

use Illuminate\Database\Eloquent\Model;

class ProyectoUsuariosModel extends Model
{
    protected $primaryKey = 'ID_PROYECTO_USUARIO';
    protected $table = 'proyectoUsuarios';
    protected $fillable = [
        'PROYECTO_ID',
        'USUARIO_ID',
        'SERVICIO_HI',
        'SERVICIO_PSICO',
        'SERVICIO_ERGO',
        'ACTIVO',
        
    ];
}
