<?php

namespace App\modelos\recsensorial;

use Illuminate\Database\Eloquent\Model;

class recsensorialModel extends Model
{
    protected $table = 'recsensorial';
    protected $fillable = [
        'recsensorial_alcancefisico',
        'recsensorial_alcancequimico',
        'recsensorial_foliofisico',
        'recsensorial_folioquimico',
        'recsensorial_tipocliente',
        'cliente_id',
        'catregion_id',
        'catsubdireccion_id',
        'catgerencia_id',
        'catactivo_id',
        'recsensorial_empresa',
        'recsensorial_codigopostal',
        'recsensorial_rfc',
        'recsensorial_ordenservicio',
        'recsensorial_representantelegal',
        'recsensorial_representanteseguridad',
        'recsensorial_instalacion',
        'recsensorial_direccion',
        'recsensorial_coordenadas',
        'recsensorial_fechainicio',
        'recsensorial_fechafin',
        'recsensorial_actividadprincipal',
        'recsensorial_descripcionproceso',
        'recsensorial_obscategorias',
        'recsensorial_elabora1',
        'recsensorial_elabora2',
        'recsensorial_quimicovalidado',
        'recsensorial_quimicofechavalidacion',
        'recsensorial_quimicopersonavalida',
        'recsensorial_pdfvalidaquimicos',
        'recsensorial_fotoplano',
        'recsensorial_fotoubicacion',
        'recsensorial_fotoinstalacion',
        'recsensorial_reconocimientofisicospdf',
        'recsensorial_reconocimientoquimicospdf',
        'recsensorial_fisicosimprimirbloqueado',
        'recsensorial_quimicosimprimirbloqueado',
        'recsensorial_repfisicos1nombre',
        'recsensorial_repfisicos1cargo',
        'recsensorial_repfisicos1doc',
        'recsensorial_repfisicos2nombre',
        'recsensorial_repfisicos2cargo',
        'recsensorial_repfisicos2doc',
        'recsensorial_repquimicos1nombre',
        'recsensorial_repquimicos1cargo',
        'recsensorial_repquimicos1doc',
        'recsensorial_repquimicos2nombre',
        'recsensorial_repquimicos2cargo',
        'recsensorial_repquimicos2doc',
        'recsensorial_bloqueado',
        'recsensorial_eliminado',
        'contrato_id',
        'recsensorial_ordenTrabajoLicitacion',
        'json_personas_elaboran',
        'informe_del_cliente',
        'requiere_contrato',
        'recsensorial_quimicoFinalizado',
        'autorizado',
        'ordentrabajo_id',
        'proyecto_folio',
        'recsensorial_documentocliente',
        'recsensorial_personaelaboro',
        'recsensorial_fechaelaboracion',
        'recsensorial_documentoclientevalidacion',
        'recsensorial_personavalido',
        'recsensorial_fechavalidacion',
        'descripcion_cliente',
        'descripcion_contrato'
    ];

    //=============== SINCRONIZACION CON TABLAS ===================

    public function recsensorialpruebas()
    {
        return $this->belongsToMany(\App\modelos\catalogos\Cat_pruebaModel::class, 'recsensorialpruebas', 'recsensorial_id', 'catprueba_id');
    }

    //=============== RELACION A CATALOGOS ===================

    // public function catcontrato()
    // {
    //     return $this->belongsTo(\App\modelos\recsensorial\catcontratoModel::class);
    // }

    public function cliente()
    {
        return $this->belongsTo(\App\modelos\clientes\clienteModel::class);
    }

    public function catregion()
    {
        return $this->belongsTo(\App\modelos\recsensorial\catregionModel::class);
    }

    public function catsubdireccion()
    {
        return $this->belongsTo(\App\modelos\recsensorial\catsubdireccionModel::class);
    }

    public function catgerencia()
    {
        return $this->belongsTo(\App\modelos\recsensorial\catgerenciaModel::class);
    }

    public function catactivo()
    {
        return $this->belongsTo(\App\modelos\recsensorial\catactivoModel::class);
    }

    public function catprueba()
    {
        return $this->belongsTo(\App\modelos\recsensorial\Cat_pruebaModel::class);
    }


    //=============== RELACION A TABLAS ===================

    // public function domicilio()
    // {
    // 	return $this->hasMany(\App\modelos\catalogos\DomicilioModel::class);
    // }
}
