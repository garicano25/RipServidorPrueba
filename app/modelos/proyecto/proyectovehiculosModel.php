<?php

namespace App\modelos\proyecto;

use Illuminate\Database\Eloquent\Model;

class proyectovehiculosModel extends Model
{

    protected $table = 'proyectovehiculos';
    protected $fillable = [
        'proyecto_id',
        'proyectovehiculo_revision',
        'proyectovehiculo_autorizado',
        'proyectovehiculo_autorizadonombre' ,
        'proyectovehiculo_autorizadofecha' ,
        'proyectovehiculo_cancelado',
        'proyectovehiculo_canceladonombre' ,
        'proyectovehiculo_canceladofecha' ,
        'proyectovehiculo_canceladoobservacion' 
    ];
}
