<?php

namespace App\modelos\reportes;

use Illuminate\Database\Eloquent\Model;

class reporteserviciopersonalcondicioninseguracategoriasModel extends Model
{
    protected $table = 'reporteserviciopersonalcondicioninseguracategorias';
    protected $fillable = [
          'reporteserviciopersonalcondicioninsegura_id'
        , 'reportecategoria_id'
    ];


    public function reportecategoria()
    {
        return $this->belongsTo(\App\modelos\reportes\reportecategoriaModel::class);
    }
}
