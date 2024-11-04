<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteAlimentosPuntosSuperficiesInertesModel extends Model
{
    protected $primaryKey = 'ID_PUNTO_INERTES';
    protected $table = 'puntosSuperficiesInertesInformes';
    protected $fillable = [
        'PROYECTO_ID',
        'PUNTO_MEDICION_INERTES',
        'AREA_INERTES',
        'UBICACION_INERTES',
        'COLIFORME_TOTALES_INERTES',
        'FECHA_MEDICION_INERTES_TOTALES',
       'UNIDAD_INERTES_TOTALES',
       'METODO_INERTES_TOTALES',
        'TRABAJADORES_INERTES_TOTALES',
       'CONCENTRACION_INERTES_TOTALES',
       'CONCENTRACION_PERMISIBLE_TOTALES',
        'COLIFORME_FECALES_INERTES',
        'FECHA_MEDICION_INERTES_FECALES',
       'UNIDAD_INERTES_FECALES',
       'METODO_INERTES_FECALES',
        'TRABAJADORES_INERTES_FECALES',
       'CONCENTRACION_INERTES_FECALES',
       'CONCENTRACION_PERMISIBLE_FECALES',
    ];

}
