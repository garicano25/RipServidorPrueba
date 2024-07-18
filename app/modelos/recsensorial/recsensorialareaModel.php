<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class recsensorialareaModel extends Model
{
    protected $table = 'recsensorialarea';
    protected $fillable = [
		'recsensorial_id',
        'recsensorialarea_nombre',
        'recsensorialarea_condicion',
        'recsensorialarea_caracteristica',
        'recsensorialarea_extraccionaire',
        'recsensorialarea_inyeccionaire',
        'recsensorialarea_generacioncontaminante',
        'recsensorialarea_controlestecnicos',
        'recsensorialarea_controlesadministrativos',
        'RECSENSORIALAREA_PROCESO',
        'JERARQUIACONTROL',
        'CONTROLESJERARQUIA_DESCRIPCION',
        'RECSENSORIAL_DATOSAREA',
        'DESCRIPCION_AREA'
    ];

    //=============== SINCRONIZACION CON TABLAS ===================

    public function recsensorialareapruebassincronizacion()
    {
        return $this->belongsToMany(\App\modelos\catalogos\Cat_pruebaModel::class, 'recsensorialareapruebas', 'recsensorialarea_id', 'catprueba_id');
    }

    // public function recsensorialareacategoriassincronizacion()
    // {
    //     return $this->belongsToMany(\App\modelos\recsensorial\recsensorialcategoriaModel::class, 'recsensorialareacategorias', 'recsensorialarea_id', 'recsensorialcategoria_id', 'recsensorialareacategorias_actividad', 'recsensorialareacategorias_geh', 'recsensorialareacategorias_total', 'recsensorialareacategorias_tiempoexpo', 'recsensorialareacategorias_frecuenciaexpo');
    // }

    //=============== RELACION CATALOGOS ===================

    public function recsensorialareapruebas()
    {
    	return $this->hasMany(\App\modelos\recsensorial\recsensorialareapruebasModel::class, 'recsensorialarea_id');
    }


    public function recsensorialareacategorias()
    {
        return $this->hasMany(\App\modelos\recsensorial\recsensorialareacategoriasModel::class, 'recsensorialarea_id');
    }
}
