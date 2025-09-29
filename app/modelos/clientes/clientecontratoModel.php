<?php

namespace App\modelos\clientes;

use Illuminate\Database\Eloquent\Model;

class clientecontratoModel extends Model
{
    protected $primaryKey = 'ID_CONTRATO';
    protected $table = 'contratos_clientes';
    protected $fillable = [
        'CLIENTE_ID',
        'TIPO_SERVICIO',
        'NOMBRE_CONTACTO',
        'CARGO_CONTACTO',
        'CORREO_CONTACTO',
        'TELEFONO_CONTACTO',
        'CELULAR_CONTACTO',
        'NUMERO_CONTRATO',
        'DESCRIPCION_CONTRATO',
        'MONEDA_MONTO',
        'MONTO',
        'CONTRATO_PLANTILLA_LOGOIZQUIERDO',
        'CONTRATO_PLANTILLA_LOGODERECHO',
        'CONTRATO_PLANTILLA_ENCABEZADO',
        'CONTRATO_PLANTILLA_EMPRESARESPONSABLE',
        'CONTRATO_PLANTILLA_PIEPAGINA',
        'FECHA_INICIO',
        'FECHA_FIN',
        'RUTA_CONTRATO',
        'CONCLUIDO',
        'ACTIVO',
        'REPRESENTANTE_LEGAL_CONTRATO'
    ];
}
