<?php

namespace App\modelos\eppcatalogo;

use Illuminate\Database\Eloquent\Model;

class catcertificacioneppModel extends Model
{
    protected $table = 'cat_certificacionespp';
    protected $primaryKey = 'ID_CERTIFICACIONES_EPP';
    protected $fillable = [
        'NOMBRE_CERTIFICACION',
        'DESCRIPCION_CERTIFICACION',
        'FOTO_CERTIFICACION',
        'ACTIVO'
    ];
}
