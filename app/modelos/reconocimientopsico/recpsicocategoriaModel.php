<?php

namespace App\modelos\reconocimientopsico;

use Illuminate\Database\Eloquent\Model;

class recpsicocategoriaModel extends Model
{
    //

    protected $table = 'recpsicocategoria';
    protected $fillable = [
        'RECPSICO_ID',
        'catdepartamento_id',
        'catmovilfijo_id',
        'RECPSICO_NOMBRECATEGORIA',
        'SUMAHORASJORNADA',
        'JSON_TURNOS'
    ];

    public function catdepartamento()
    {
        return $this->belongsTo(\App\modelos\recsensorial\catdepartamentoModel::class);
    }

    public function catmovilfijo()
    {
        return $this->belongsTo(\App\modelos\recsensorial\catmovilfijoModel::class);
    }

}
