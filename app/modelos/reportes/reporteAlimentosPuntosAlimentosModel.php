<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteAlimentosPuntosAlimentosModel extends Model
{
    protected $primaryKey = 'ID_PUNTO_ALIMENTOS';
    protected $table = 'puntosAlimentosInformes';
    protected $fillable = [
        'PROYECTO_ID',
        'PUNTO_MEDICION_ALIMENTOS',
        'AREA_ALIMENTOS',
        'UBICACION_ALIMENTOS',

        'COLIFORME_TOTALES_ALIMENTOS',
        'FECHA_MEDICION_ALIMENTOS_TOTALES',
        'UNIDAD_ALIMENTOS_TOTALES',
        'METODO_ALIMENTOS_TOTALES',
        'TRABAJADORES_ALIMENTOS_TOTALES',
        'CONCENTRACION_ALIMENTOS_TOTALES',
        'CONCENTRACION_PERMISIBLE_TOTALES', 
        
        'COLIFORME_FECALES_ALIMENTOS',
        'FECHA_MEDICION_ALIMENTOS_FECALES',
        'UNIDAD_ALIMENTOS_FECALES',
        'METODO_ALIMENTOS_FECALES',
        'TRABAJADORES_ALIMENTOS_FECALES',
        'CONCENTRACION_ALIMENTOS_FECALES',
        'CONCENTRACION_PERMISIBLE_FECALES',

        'PARAMETRO_COLOR',
        'UNIDAD_COLOR',
        'METODO_COLOR',
        'CONCENTRACION_COLOR', 
        
        'PARAMETRO_OLOR',
        'UNIDAD_OLOR',
        'METODO_OLOR',
        'CONCENTRACION_OLOR',   
        
        'PARAMETRO_SABOR',
        'UNIDAD_SABOR',
        'METODO_SABOR',
        'CONCENTRACION_SABOR',
    ];
}
