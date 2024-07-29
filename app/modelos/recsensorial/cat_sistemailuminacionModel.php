<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class cat_sistemailuminacionModel extends Model
{
    protected $primaryKey = 'ID_SISTEMA_ILUMINACION';
    protected $table = 'cat_sistema_iluminacion';
    protected $fillable = [
        'NOMBRE',
        'DESCRIPCION',
        'ACTIVO'
    ];
}
