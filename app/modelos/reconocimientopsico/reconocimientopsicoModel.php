<?php

namespace App\modelos\reconocimientopsico;

use Illuminate\Database\Eloquent\Model;

class reconocimientopsicoModel extends Model
{
    //
    protected $table = 'reconocimientopsico';
    protected $fillable = [
        'tipocliente',
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
