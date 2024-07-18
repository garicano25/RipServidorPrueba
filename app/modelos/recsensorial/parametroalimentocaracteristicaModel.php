<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class parametroalimentocaracteristicaModel extends Model
{
    protected $table = 'parametroalimentocaracteristica';
    protected $fillable = [
		'parametroalimento_id',
		'catparametroalimentocaracteristica_id'
    ];


    //=============================================================


    public function catparametroalimentocaracteristica()
    {
        return $this->belongsTo(\App\modelos\recsensorial\catparametroalimentocaracteristicaModel::class, 'catparametroalimentocaracteristica_id');
    }
    
}
