<?php

namespace App\modelos\proyecto;

use Illuminate\Database\Eloquent\Model;

class proyectovehiculoshistorialModel extends Model
{
    protected $table = 'proyectovehiculoshistorial';
    protected $fillable = [
        'proyecto_id',
        'proveedor_id',
        'proyectovehiculo_revision',
        'vehiculo_id'
    ];
}
