<?php

namespace App\modelos\catalogos;

use Illuminate\Database\Eloquent\Model;

class AcreditacionalcanceModel extends Model
{
    protected $table = 'acreditacionalcance';
    protected $fillable = [
        'proveedor_id',
        'acreditacion_id',
        'acreditacionAlcance_tipo',
        'prueba_id',
        'acreditacionAlcance_agente',
        'acreditacionAlcance_agentetipo',
        'alcace_aprovacion_id',
        'acreditacionAlcance_Procedimiento',
        'acreditacionAlcance_Metodo',
        'requiere_aprobacion',
        'acreditacionAlcance_Norma',
        'acreditacionAlcance_Descripcion',
        'acreditacionAlcance_Observacion',
        'acreditacionAlcance_Eliminado'
    ];

    public function proveedor()
    {
        return $this->belongsTo(\App\modelos\catalogos\ProveedorModel::class);
    }

    public function acreditacion()
    {
        return $this->belongsTo(\App\modelos\catalogos\AcreditacionModel::class, 'acreditacion_id');
    }

    public function prueba()
    {
        return $this->belongsTo(\App\modelos\catalogos\Cat_pruebaModel::class, 'prueba_id');
    }
}
