<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class matrizlaboral extends Model
{
    protected $table = 'matriz_laboral';

    protected $fillable = [
        'proyecto_id',
        'area_id',
        'fila_id',
        'agente',
        'categoria',
        'numero_trabajadores',
        'tiempo_exposicion',
        'indice_peligro',
        'indice_exposicion',
        'riesgo',
        'valor_lmpnmp',
        'cumplimiento',
        'medidas_json',
    ];

  
}
