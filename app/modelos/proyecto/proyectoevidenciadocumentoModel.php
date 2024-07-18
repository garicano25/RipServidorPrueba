<?php

namespace App\modelos\proyecto;

use Illuminate\Database\Eloquent\Model;

class proyectoevidenciadocumentoModel extends Model
{
    protected $table = 'proyectoevidenciadocumento';
    protected $fillable = [
        'proyecto_id',
        'proveedor_id',
		'agente_id',
		'agente_nombre',
		'proyectoevidenciadocumento_nombre',
		'proyectoevidenciadocumento_archivo',
		'proyectoevidenciadocumento_extension'
    ];
}
