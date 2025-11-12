<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class departamentosmeldraft extends Model
{
    protected $table = 'departamentos_meldraft';

    protected $fillable = [
        'proyecto_id',
        'DEPARTAMENTO_MEL'
    ];
}
