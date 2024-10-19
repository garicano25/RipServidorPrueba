<?php

namespace App\modelos\proyecto;

use Illuminate\Database\Eloquent\Model;

class proyectovehiculosactualModel extends Model
{
    //
    protected $table = 'proyectovehiculosactual';
    protected $fillable = [
        'proyecto_id',
        'proveedor_id',
        'vehiculo_id'
    ];
}
