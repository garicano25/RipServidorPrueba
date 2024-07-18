<?php

namespace App\modelos\clientes;

use Illuminate\Database\Eloquent\Model;

class clientepartidasModel extends Model
{
    protected $table = 'contratos_partidas'; #SE RENOMBRE DE clientepartidas a contratos_partidas
    protected $fillable = [
          'CONTRATO_ID',
        'CONVENIO_ID',
            'catprueba_id'
        , 'clientepartidas_tipo'
        , 'clientepartidas_nombre'
        , 'clientepartidas_descripcion',
        'UNIDAD_MEDIDA',
        'PRECIO_UNITARIO',
        'DESCUENTO',
        'ACTIVO'

    ];


    // public function recsensorialpruebas()
    // {
    //     return $this->belongsToMany(\App\modelos\catalogos\Cat_pruebaModel::class, 'recsensorialpruebas', 'recsensorial_id', 'catprueba_id');
    // }

    public function catprueba()
    {
        return $this->belongsTo(\App\modelos\catalogos\Cat_pruebaModel::class);
    }
}
