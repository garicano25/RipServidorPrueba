<?php

namespace App\modelos\clientes;

use Illuminate\Database\Eloquent\Model;

class cronogramaActividadesModel extends Model
{
    //
    protected $primaryKey = 'ID_ACTIVIDAD';
    protected $table = 'cronogramaActividades';
    protected $fillable = [
        'PROYECTO_ID',
        'CONTRATO_ID',
        'DESCRIPCION_ACTIVIDAD',
        'FECHA_INICIO_ACTIVIDAD',
        'FECHA_FIN_ACTIVIDAD',
        'AGENTE_ACTIVIDAD_ID',
        'PUNTOS_ACTIVIDAD',
        'COLOR_ACTIVIDAD'
    ];
}
