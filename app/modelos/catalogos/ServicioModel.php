<?php

namespace App\modelos\catalogos;

use Illuminate\Database\Eloquent\Model;

class ServicioModel extends Model
{
    //
    protected $table = 'servicio';
    protected $fillable = [
		'proveedor_id',
        'servicio_numerocotizacion',
        'servicio_FechaCotizacion',
        'servicio_VigenciaCotizacion',
        'servicio_Observaciones',
        'servicio_SoportePDF',
        'servicio_Eliminado',
        'ACTIVO_COTIZACIONPROVEEDOR'
    ];

    public function proveedor()
    {
        return $this->belongsTo(\App\modelos\catalogos\ProveedorModel::class);
    }

    // public function cat_area()
    // {
    //     return $this->belongsTo(\App\modelos\catalogos\Cat_areaModel::class);
    // }

    // public function cat_prueba()
    // {
    //     return $this->belongsTo(\App\modelos\catalogos\Cat_pruebaModel::class);
    // }

    // public function acreditacion()
    // {
    //     return $this->belongsTo(\App\modelos\catalogos\AcreditacionModel::class,'cat_servicioareaacreditacion_id');
    // }
}
