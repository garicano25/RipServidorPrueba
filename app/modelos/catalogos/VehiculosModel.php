<?php

namespace App\modelos\catalogos;

use Illuminate\Database\Eloquent\Model;

class VehiculosModel extends Model
{
    protected $table = 'vehiculo';
    protected $fillable = [
        'proveedor_id',
        'vehiculo_marca',
        'vehiculo_linea',
        'vehiculo_modelo',
        'vehiculo_serie',
        'vehiculo_placa',
        'vehiculo_imagen',
        'vehiculo_Eliminado',
        'vehiculo_EstadoActivo'
    ];

    public function proveedor()
    {
        return $this->belongsTo(\App\modelos\catalogos\ProveedorModel::class);
    }
}
