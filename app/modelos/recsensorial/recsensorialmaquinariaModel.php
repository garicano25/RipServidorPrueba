<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class recsensorialmaquinariaModel extends Model
{
    protected $table = 'recsensorialmaquinaria';
    protected $fillable = [
        'recsensorial_id',
        'recsensorialarea_id',
        'recsensorialmaquinaria_nombre',
        'recsensorialmaquinaria_afecta',
        'recsensorialmaquinaria_cantidad',
        'recsensorialmaquinaria_contenido',
        'recsensorialmaquinaria_unidadMedida',

        'recsensorialmaquinaria_quimica',
        'recsensorialmaquinaria_afecta'
    ];

    public function recsensorialarea()
    {
        return $this->belongsTo(\App\modelos\recsensorial\recsensorialareaModel::class);
    }
}
