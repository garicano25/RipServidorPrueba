<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class recsensorialTablaClienteInformeModel extends Model
{
    protected $primaryKey = 'ID_TABLA_INFORME_CLIENTE';
    protected $table = 'recsensorial_tablaClientes_informes';
    protected $fillable = [
        'AREA_ID',
        'RECONOCIMIENTO_ID',
        'CATEGORIA_ID',
        'SUSTANCIA_ID',
        'PPT',
        'CT',
        'PUNTOS',
        'ACTIVO'



    ];
}
