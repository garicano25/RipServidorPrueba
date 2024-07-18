<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class catPartesCuerpoDescripcionModel extends Model
{
    protected $primaryKey = 'ID_PARTESCUERPO_DESCRIPCION';
    protected $table = 'catpartescuerpo_descripcion';
    protected $fillable = [
        'PARTECUERPO_ID',
        'CLAVE_EPP',
        'TIPO_RIEGO',
        'ACTIVO'
    ];
}
