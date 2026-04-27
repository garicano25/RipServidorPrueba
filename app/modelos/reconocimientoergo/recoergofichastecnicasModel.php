<?php

namespace App\modelos\reconocimientoergo;

use Illuminate\Database\Eloquent\Model;

class recoergofichastecnicasModel extends Model
{
    protected $primaryKey = 'ID_FICHAS_TECNICAS';
    protected $table = 'recoergo_fichastecnicas';
    protected $fillable = [
        'RECO_ID',
        'CATEGORIA_ID_FICHA',
        'CAT_DEPARTAMENTO_FICHA',
        'CAT_AREAS_FICHA',
        'NOMBRE_EMPLEADO_FICHA',
        'NO_EMPLEADO_FICHA',
        'SEXO_EMPLEADO_FICHA',
        'FECHA_NACIMIENTO',
        'EDAD_EMPLEADO_FICHA',
        'PESO_FICHA',
        'TALLA_FICHA',
        'REGIMEN_CONTRACTUAL_FICHA',
        'JORNADA_EMPLEADO_FICHA',
        'TURNO_EMPLEADO_FICHA',
        'TIEMPO_EMPRESA_FICHA',
        'ANTIGUEDAD_CATEOGORIA_FICHA',
        'P1_CARGA_MAYOR_3KG',
        'P2_FRECUENCIA_CARGA',
        'P3_MANIPULACION_CARGA',
        'JSON_ACTIVIDADES',
        'JSON_FICHAS',
        'ACTIVO'

    ];


    protected $casts = [
        'CAT_AREAS_FICHA' => 'array',
        
    ];


}
