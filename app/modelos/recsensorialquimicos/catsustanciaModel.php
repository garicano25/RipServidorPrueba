<?php

namespace App\modelos\recsensorialquimicos;

use Illuminate\Database\Eloquent\Model;

class catsustanciaModel extends Model
{
    protected $table = 'catsustancia';
    protected $fillable = [
        'catsustancia_nombre',
        'catsustancia_nombreComun',
        'catestadofisicosustancia_id',
        'catvolatilidad_id',
        'catviaingresoorganismo_id',
        'catcategoriapeligrosalud_id',
        'catgradoriesgosalud_id',
        'catsustancia_hojaseguridadpdf',
        'catsustancia_activo',
        'catsustancia_fabricante',
        'catsustancia_puntoEbullicion',
        'catsustancia_solidoTipo',
        'catTipoClasificacion',
        'catClasificacionRiesgo',
        'catTemOperacion'
    ];

    //=============== RELACION OTROS CATALOGOS ===================

    public function catestadofisicosustancia()
    {
        return $this->belongsTo(\App\modelos\recsensorialquimicos\catestadofisicosustanciaModel::class);
    }

    public function catvolatilidad()
    {
        return $this->belongsTo(\App\modelos\recsensorialquimicos\catvolatilidadModel::class);
    }

    public function catviaingresoorganismo()
    {
        return $this->belongsTo(\App\modelos\recsensorialquimicos\catviaingresoorganismoModel::class);
    }

    public function catcategoriapeligrosalud()
    {
        return $this->belongsTo(\App\modelos\recsensorialquimicos\catcategoriapeligrosaludModel::class);
    }

    public function catgradoriesgosalud()
    {
        return $this->belongsTo(\App\modelos\recsensorialquimicos\catgradoriesgosaludModel::class);
    }

    //=============== RELACION TABLA COMPONENTES ===================

    public function catsustanciacomponente()
    {
        return $this->hasMany(\App\modelos\recsensorialquimicos\catsustanciacomponenteModel::class, 'catsustancia_id');
    }
}
