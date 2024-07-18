<?php

namespace App\modelos\catalogos;

use Illuminate\Database\Eloquent\Model;

class EquipoModel extends Model
{
    protected $table = 'equipo';
    protected $fillable = [
        'proveedor_id',
        'equipo_uso',
        'equipo_Descripcion',
        'equipo_Marca',
        'equipo_Modelo',
        'equipo_Serie',
        'equipo_Tipo',
        'unidad_medida',
        'equipo_PesoNeto',
        'equipo_CostoAprox',
        'equipo_TipoCalibracion',
        'equipo_FechaCalibracion',
        'equipo_VigenciaCalibracion',
        'requiere_calibracion',
        'numero_inventario',
        'equipo_EstadoActivo',
        'folio_factura',
        'equipo_imagen',
        'equipo_Eliminado'
    ];

    public function proveedor()
    {
        return $this->belongsTo(\App\modelos\catalogos\ProveedorModel::class);
    }

    // public function acreditacionalcance()
    // {
    //     return $this->belongsTo(\App\modelos\catalogos\AcreditacionalcanceModel::class);
    // }

    // public function cat_area()
    // {
    //     return $this->belongsTo(\App\modelos\catalogos\Cat_areaModel::class,'equipoacreditacion_id');
    // }

    // public function cat_prueba()
    // {
    //     return $this->belongsTo(\App\modelos\catalogos\Cat_pruebaModel::class,'cat_prueba_id');
    // }
}
