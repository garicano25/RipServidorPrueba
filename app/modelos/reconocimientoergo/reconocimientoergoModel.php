<?php

namespace App\modelos\reconocimientoergo;

use Illuminate\Database\Eloquent\Model;

class reconocimientoergoModel extends Model
{
    //

    protected $table = 'reconocimientoergo';
    protected $fillable = [
        'tipocliente',
        'higiene',
        'cliente_id',
        'descripcion_cliente',
        'empresa',
        'codigopostal',
        'rfc',
        'ordenservicio',
        'representantelegal',
        'representanteseguridad',
        'instalacion',
        'direccion',
        'coordenadas',
        'fechainicio',
        'fechafin',
        'actividadprincipal',
        'descripcionproceso',
        'observaciones',
        'fotoplano',
        'fotoubicacion',
        'fotoinstalacion',
        'fotomapariesgo',
        'contrato_id',
        'descripcion_contrato',
        'informe_del_cliente',
        'requiere_contrato',
        'autorizado',
        'ordentrabajo_id',
        'proyecto_folio',
        'NOMBRE_TECNICO',
        'NOMBRE_CONTRATO',
        'CARGO_TECNICO',
        'CARGO_CONTRATO',
        'TECNICO_DOC',
        'CONTRATO_DOC'
    ];

}
