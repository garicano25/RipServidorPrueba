<?php

namespace App\modelos\reconocimientopsico;

use Illuminate\Database\Eloquent\Model;

class recopsicocategoriaModel extends Model
{
    //
    protected $primaryKey = 'ID_RECOPSICOCATEGORIA';
    protected $table = 'recopsicocategoria';
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
