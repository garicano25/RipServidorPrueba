<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteAlimentosPuntosSuperficiesVivasModel extends Model
{
    protected $primaryKey = 'ID_PUNTO_VIVAS';
    protected $table = 'puntosSuperficiesVivasInformes';
    protected $fillable = [
        'PROYECTO_ID',
        'PUNTO_MEDICION_VIVAS',
        'AREA_VIVAS',
        'UBICACION_VIVAS',
        'COLIFORME_TOTALES_VIVAS',
        'FECHA_MEDICION_VIVAS_TOTALES',
        'UNIDAD_VIVAS_TOTALES',
        'METODO_VIVAS_TOTALES',
        'TRABAJADORES_VIVAS_TOTALES',
        'CONCENTRACION_VIVAS_TOTALES',
        'CONCENTRACION_PERMISIBLE_TOTALES',
        'COLIFORME_FECALES_VIVAS',
        'FECHA_MEDICION_VIVAS_FECALES',
        'UNIDAD_VIVAS_FECALES',
        'METODO_VIVAS_FECALES',
        'TRABAJADORES_VIVAS_FECALES',
        'CONCENTRACION_VIVAS_FECALES',
        'CONCENTRACION_PERMISIBLE_FECALES',
    ];
}
