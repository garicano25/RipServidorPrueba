<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class recsensorialTablaClienteProporcionadoModel extends Model
{


    protected $primaryKey = 'ID_TABLA_CLIENTE_PROPORCIONADO';
    protected $table = 'recsensorial_tablaClientes_proporcionado';
    protected $fillable = [
        'RECONOCIMIENTO_ID',
        'AREA_PROPORCIONADACLIENTE',
        'CATEGORIA_PROPORCIONADACLIENTE',
        'PRODUCTO_PROPORCIONADACLIENTE',
        'SUSTANCIA_ID',
        'PPT_PROPORCIONADACLIENTE',
        'CT_PROPORCIONADACLIENTE',
        'PUNTOS_PROPORCIONADACLIENTE',
        'ACTIVO'



    ];
}
