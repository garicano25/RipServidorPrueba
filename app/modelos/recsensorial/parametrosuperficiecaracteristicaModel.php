<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class parametrosuperficiecaracteristicaModel extends Model
{
    protected $table = 'parametrosuperficiecaracteristica';
    protected $fillable = [
		'parametrosuperficie_id',
		'catparametrosuperficiecaracteristica_id'
    ];


    //=============================================================


    public function catparametroalimentocaracteristica()
    {
        return $this->belongsTo(\App\modelos\recsensorial\catparametrosuperficiecaracteristicaModel::class, 'catparametrosuperficiecaracteristica_id');
    }
    
}
