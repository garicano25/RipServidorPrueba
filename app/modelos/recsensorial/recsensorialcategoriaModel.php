<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class recsensorialcategoriaModel extends Model
{
    protected $table = 'recsensorialcategoria';
    protected $fillable = [
        'recsensorial_id',
        'catdepartamento_id',
        'catmovilfijo_id',
        'recsensorialcategoria_nombrecategoria',
        'sumaHorasJornada',
        'JSON_TURNOS'
    ];

    //=============== CATALOGOS ===============

    public function catdepartamento()
    {
        return $this->belongsTo(\App\modelos\recsensorial\catdepartamentoModel::class);
    }

    public function catmovilfijo()
    {
        return $this->belongsTo(\App\modelos\recsensorial\catmovilfijoModel::class);
    }
}
