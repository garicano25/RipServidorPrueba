<?php

namespace App\modelos\clientes;

use Illuminate\Database\Eloquent\Model;

class clienteModel extends Model
{
    protected $table = 'cliente';
    protected $fillable = [

          'cliente_RazonSocial',
            'DOCUMENTO_CIERRE_ID'
        , 'cliente_NombreComercial'
        , 'cliente_DomicilioFiscal'
        , 'cliente_LineaNegocios'
        , 'cliente_GiroComercial'
        , 'cliente_Rfc'
        , 'cliente_CiudadPais'
        , 'cliente_RepresentanteLegal'
        , 'cliente_PaginaWeb'
        , 'region_id'
        , 'subdireccion_id'
        , 'gerencia_id'
        , 'activo_id'
        , 'cliente_Pais'
        , 'cliente_RazonSocialContrato'
        // , 'cliente_descripcioncontrato'
        // , 'cliente_fechainicio'
        // , 'cliente_fechafin'
        // , 'cliente_montomxn'
        // , 'cliente_montousd'
        // , 'cliente_plantillalogoizquierdo'
        // , 'cliente_plantillalogoderecho'
        // , 'cliente_plantillaencabezado'
        // , 'cliente_plantillaempresaresponsable'
        // , 'cliente_plantillapiepagina'
        , 'cliente_Bloqueado'
        , 'cliente_Eliminado'
        ,'requiere_estructuraCliente'
        // ,'organizacional1_etiqueta'
        // ,'organizacional1_opciones'
        // ,'organizacional2_etiqueta'
        // ,'organizacional2_opciones'
        // ,'organizacional3_etiqueta'
        // ,'organizacional3_opciones'
        // ,'organizacional4_etiqueta'
        // ,'organizacional4_opciones'
        // ,'organizacional5_etiqueta'
        // ,'organizacional5_opciones'
        ,'cliente_cp'
        , 'cliente_Bloqueado'
    ];
}
