<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class catFormatosCampo extends Model
{
    protected $primaryKey = 'ID_FORMATO';
    protected $table = 'catFormatos_campo';
    protected $fillable = [
        'NOMBRE',
        'DESCRIPCION',
        'RUTA_PDF',
        'ACTIVO'
    ];
}
